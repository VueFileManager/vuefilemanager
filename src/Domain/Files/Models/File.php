<?php
namespace Domain\Files\Models;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Database\Factories\FileFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Domain\Traffic\Actions\RecordUploadAction;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereUserId($user_id)
 * @method static whereId($id)
 * @method static find(mixed $id)
 * @method static where(string $string, string $user_id)
 * @property string id
 * @property string user_id
 * @property string parent_id
 * @property string thumbnail
 * @property int filesize
 * @property string type
 * @property string basename
 * @property string name
 * @property string mimetype
 * @property string author
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class File extends Model
{
    use SoftDeletes;
    use Searchable;
    use HasFactory;
    use Sortable;

    public ?string $sharedAccess = null;
    public ?string $uploadRequestAccess = null;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'thumbnail',
        'file_url',
    ];

    public array $sortable = [
        'name',
        'created_at',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }

    public function setNameAttribute($name): void
    {
        $this->attributes['name'] = mb_convert_encoding($name, 'UTF-8');
    }

    /**
     * Set shared routes with public access
     */
    public function setSharedPublicUrl(string $token)
    {
        $this->sharedAccess = $token;
    }

    /**
     * Set upload request routes with public access
     */
    public function setUploadRequestPublicUrl(string $token)
    {
        $this->uploadRequestAccess = $token;
    }

    /**
     * Format thumbnail url
     */
    public function getThumbnailAttribute(): array | null
    {
        $links = [];
        $thumbnail_sizes = collect(config('vuefilemanager.image_sizes'))
            ->collapse()
            ->all();

        // Generate thumbnail link for external storage service
        if ($this->type === 'image' && isStorageDriver(['s3', 'azure'])) {
            foreach ($thumbnail_sizes as $item) {
                $filePath = "files/{$this->user_id}/{$item['name']}-{$this->basename}";

                $links[$item['name']] = Storage::temporaryUrl($filePath, now()->addHour());
            }

            return $links;
        }

        // Generate thumbnail link for local storage
        if ($this->type === 'image') {
            foreach ($thumbnail_sizes as $item) {
                $route = route('thumbnail', ['name' => $item['name'] . '-' . $this->basename]);

                // Set shared public url
                if ($this->sharedAccess) {
                    $route .= "/shared/$this->sharedAccess";
                }

                // Set upload request public url
                if ($this->uploadRequestAccess) {
                    $route .= "/upload-request/$this->uploadRequestAccess";
                }

                $links[$item['name']] = $route;
            }

            return $links;
        }

        return null;
    }

    /**
     * Format file url
     */
    public function getFileUrlAttribute(): string
    {
        // Get thumbnail from local storage
        $route = route('file', ['name' => $this->attributes['basename']]);

        // Set shared public url
        if ($this->sharedAccess) {
            return "$route/shared/$this->sharedAccess";
        }

        // Set upload request public url
        if ($this->uploadRequestAccess) {
            $route .= "/upload-request/$this->uploadRequestAccess";
        }

        return $route;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id', 'id');
    }

    public function getLatestParent()
    {
        if ($this->parent) {
            return $this->parent->getLatestParent();
        }

        return $this;
    }

    public function shared(): HasOne
    {
        return $this->hasOne(Share::class, 'item_id', 'id');
    }

    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function exif(): HasOne
    {
        return $this->hasOne(Exif::class);
    }

    public function toSearchableArray(): array
    {
        $name = mb_convert_encoding(
            mb_strtolower($this->name, 'UTF-8'),
            'UTF-8'
        );

        $trigram = (new TNTIndexer)
            ->buildTrigrams(implode(', ', [$name]));

        return [
            'id'         => $this->id,
            'name'       => $name,
            'nameNgrams' => $trigram,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->id = (string) Str::uuid();

            // Store upload record
            resolve(RecordUploadAction::class)($file->filesize, $file->user_id);
        });

        static::deleting(function ($file) {
            if ($file->isForceDeleting()) {
                $file->exif()->forceDelete();
            }
        });
    }
}
