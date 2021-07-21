<?php
namespace Domain\Files\Models;

use ByteUnits\Metric;
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
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereUserId($user_id)
 * @method static whereId($id)
 * @property string folder_id
 * @property string id
 */
class File extends Model
{
    use Searchable;
    use SoftDeletes;
    use Sortable;
    use HasFactory;

    public ?string $public_access = null;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'file_url',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $hidden = [
        'author_id',
    ];

    public $sortable = [
        'name',
        'created_at',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): FileFactory
    {
        return FileFactory::new();
    }

    /**
     * Set routes with public access
     */
    public function setPublicUrl(string $token)
    {
        $this->public_access = $token;
    }

    /**
     * Format created at date
     */
    public function getCreatedAtAttribute(): string
    {
        return format_date(
            set_time_by_user_timezone($this->attributes['created_at']),
            __t('time')
        );
    }

    /**
     * Format deleted at date reformat
     */
    public function getDeletedAtAttribute(): string | null
    {
        if (! $this->attributes['deleted_at']) {
            return null;
        }

        return format_date(
            set_time_by_user_timezone($this->attributes['deleted_at']),
            __t('time')
        );
    }

    /**
     * Format fileSize
     */
    public function getFilesizeAttribute(): string
    {
        return Metric::bytes($this->attributes['filesize'])->format();
    }

    /**
     * Format thumbnail url
     */
    public function getThumbnailAttribute(): string | null
    {
        // Get thumbnail from external storage
        if ($this->attributes['thumbnail'] && ! is_storage_driver(['local'])) {
            return Storage::temporaryUrl("files/$this->user_id/{$this->attributes['thumbnail']}", now()->addHour());
        }

        // Get thumbnail from local storage
        if ($this->attributes['thumbnail']) {
            // Thumbnail route
            $route = route('thumbnail', ['name' => $this->attributes['thumbnail']]);

            if ($this->public_access) {
                return "$route/$this->public_access";
            }

            return $route;
        }

        return null;
    }

    /**
     * Format file url
     */
    public function getFileUrlAttribute(): string
    {
        // Get file from external storage
        if (! is_storage_driver(['local'])) {
            $file_pretty_name = is_storage_driver('backblaze')
                ? Str::snake(mb_strtolower($this->attributes['name']))
                : get_pretty_name($this->attributes['basename'], $this->attributes['name'], $this->attributes['mimetype']);

            $header = [
                'ResponseAcceptRanges'       => 'bytes',
                'ResponseContentType'        => $this->attributes['mimetype'],
                'ResponseContentLength'      => $this->attributes['filesize'],
                'ResponseContentRange'       => 'bytes 0-600/' . $this->attributes['filesize'],
                'ResponseContentDisposition' => 'attachment; filename=' . $file_pretty_name,
            ];

            return Storage::temporaryUrl("files/$this->user_id/{$this->attributes['basename']}", now()->addDay(), $header);
        }

        // Get thumbnail from local storage
        $route = route('file', ['name' => $this->attributes['basename']]);

        if ($this->public_access) {
            return "$route/$this->public_access";
        }

        return $route;
    }

    /**
     * Index file
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();
        $name = Str::slug($array['name'], ' ');

        return [
            'id'         => $this->id,
            'name'       => $name,
            'nameNgrams' => utf8_encode((new TNTIndexer)->buildTrigrams(implode(', ', [$name]))),
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }

    public function folder(): HasOne
    {
        return $this->hasOne(Folder::class, 'id', 'folder_id');
    }

    public function shared(): HasOne
    {
        return $this->hasOne(Share::class, 'item_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->id = (string) Str::uuid();
        });
    }
}
