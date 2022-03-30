<?php
namespace Domain\Folders\Models;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Laravel\Scout\Searchable;
use Domain\Sharing\Models\Share;
use Kyslik\ColumnSortable\Sortable;
use Database\Factories\FolderFactory;
use Illuminate\Database\Eloquent\Model;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use Domain\Teams\Models\TeamFolderInvitation;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static whereUserId(int|string|null $id)
 * @method static find(mixed $id)
 * @method static where(string $key, string $value)
 * @method static findOrFail(string $id)
 * @method static create(array $array)
 * @property string id
 * @property string user_id
 * @property string parent_id
 * @property string name
 * @property string color
 * @property string emoji
 * @property string author
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property bool team_folder
 */
class Folder extends Model
{
    use SoftDeletes;
    use Searchable;
    use HasFactory;
    use Sortable;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'items',
        'trashed_items',
    ];

    protected $casts = [
        'emoji'       => 'array',
        'team_folder' => 'boolean',
    ];

    public $sortable = [
        'name',
        'created_at',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): FolderFactory
    {
        return FolderFactory::new();
    }

    public function setNameAttribute($name): void
    {
        $this->attributes['name'] = mb_convert_encoding($name, 'UTF-8');
    }

    /**
     * Counts how many folder have items
     */
    public function getItemsAttribute(): int
    {
        $folders = $this->folders()
            ->count();

        $files = $this->files()
            ->count();

        return $folders + $files;
    }

    /**
     * Counts how many folder have items
     */
    public function getTrashedItemsAttribute(): int
    {
        $folders = $this->trashedFolders()
            ->count();

        $files = $this->trashedFiles()
            ->count();

        return $folders + $files;
    }

    /**
     * Get parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id', 'id');
    }

    public function folderIds()
    {
        return $this->children()
            ->with('folderIds')
            ->select(['id', 'parent_id']);
    }

    /**
     * Get all files
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'parent_id', 'id');
    }

    /**
     * Get all trashed files
     */
    public function trashedFiles(): HasMany
    {
        return $this->hasMany(File::class, 'parent_id', 'id')
            ->withTrashed();
    }

    /**
     * Get all folders
     */
    public function folders(): HasMany
    {
        return $this->children()->with('folders');
    }

    /**
     * Get all trashed folders
     */
    public function trashedFolders(): HasMany
    {
        return $this->children()
            ->with('trashedFolders')
            ->withTrashed()
            ->select(['parent_id', 'id', 'name']);
    }

    /**
     * Get children
     */
    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

    /**
     * Get trashed children
     */
    public function trashedChildren(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id')
            ->withTrashed();
    }

    /**
     * Get sharing attributes
     */
    public function shared(): HasOne
    {
        return $this->hasOne(Share::class, 'item_id', 'id');
    }

    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamFolderInvitation::class, 'parent_id', 'id')
            ->where('status', 'pending');
    }

    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_folder_members', 'parent_id', 'user_id')
            ->withPivot('permission');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(Folder::class, 'id', 'parent_id');
    }

    public function getLatestParent()
    {
        if ($this->parent) {
            return $this->parent->getLatestParent();
        }

        return $this;
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

    // Delete all folder children
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });

        static::deleting(function ($item) {
            if ($item->isForceDeleting()) {
                $item
                    ->trashedChildren()
                    ->each(fn ($folder) => $folder->forceDelete());
            } else {
                $item
                    ->children()
                    ->each(fn ($folder) => $folder->delete());

                $item
                    ->files()
                    ->each(fn ($file) => $file->delete());
            }
        });

        // Restore children folders and files
        static::restoring(function ($item) {
            $item
                ->trashedChildren()
                ->each(fn ($folder) => $folder->restore());
            $item
                ->trashedFiles()
                ->each(fn ($files) => $files->restore());
        });
    }
}
