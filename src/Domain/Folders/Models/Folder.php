<?php
namespace Domain\Folders\Models;

use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Laravel\Scout\Searchable;
use Domain\Sharing\Models\Share;
use Kyslik\ColumnSortable\Sortable;
use Database\Factories\FolderFactory;
use Illuminate\Database\Eloquent\Model;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereUserId(int|string|null $id)
 */
class Folder extends Model
{
    use Searchable, SoftDeletes, Sortable, HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'items',
        'trashed_items',
        'type',
    ];

    protected $casts = [
        'emoji' => 'array',
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

    protected static function newFactory(): FolderFactory
    {
        return FolderFactory::new();
    }

    public function getTypeAttribute(): string
    {
        return 'folder';
    }

    /**
     * Index folder
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
     * Format created at date reformat
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
        return $this->hasMany(File::class, 'folder_id', 'id');
    }

    /**
     * Get all trashed files
     */
    public function trashedFiles(): HasMany
    {
        return $this->hasMany(File::class, 'folder_id', 'id')
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

    // Delete all folder children
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });

        static::deleting(function ($item) {
            if ($item->isForceDeleting()) {
                $item->trashedChildren()->each(function ($folder) {
                    $folder->forceDelete();
                });
            } else {
                $item->children()->each(function ($folder) {
                    $folder->delete();
                });

                $item->files()->each(function ($file) {
                    $file->delete();
                });
            }
        });

        static::restoring(function ($item) {
            // Restore children folders
            $item->trashedChildren()->each(function ($folder) {
                $folder->restore();
            });

            // Restore children files
            $item->trashedFiles()->each(function ($files) {
                $files->restore();
            });
        });
    }
}
