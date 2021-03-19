<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

/**
 * @method static whereUserId(int|string|null $id)
 */
class Folder extends Model
{
    use Searchable, SoftDeletes, Sortable, HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'items',
        'trashed_items',
        'type'
    ];

    protected $casts = [
        'emoji' => 'array',
    ];

    /**
     * Sortable columns
     *
     * @var string[]
     */
    public $sortable = [
        'name',
        'created_at',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function getTypeAttribute()
    {
        return 'folder';
    }

    /**
     * Index folder
     *
     * @return array
     */
    public function toSearchableArray()
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
     *
     * @return int
     */
    public function getItemsAttribute()
    {
        $folders = $this->folders()->count();
        $files = $this->files()->count();

        return $folders + $files;
    }

    /**
     * Counts how many folder have items
     *
     * @return int
     */
    public function getTrashedItemsAttribute()
    {
        $folders = $this->trashed_folders()->count();
        $files = $this->trashed_files()->count();

        return $folders + $files;
    }

    /**
     * Format created at date reformat
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return format_date(set_time_by_user_timezone($this->attributes['created_at']), __('vuefilemanager.time'));
    }

    /**
     * Format created at date reformat
     *
     * @return string
     */
    public function getDeletedAtAttribute()
    {
        if (!$this->attributes['deleted_at']) return null;

        return format_date(set_time_by_user_timezone($this->attributes['deleted_at']), __('vuefilemanager.time'));
    }

    /**
     * Get parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id', 'id');
    }

    public function folderIds()
    {
        return $this->children()->with('folderIds')->select(['id', 'parent_id']);
    }

    /**
     * Get all files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class, 'folder_id', 'id');
    }

    /**
     * Get all trashed files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trashed_files()
    {

        return $this->hasMany(File::class, 'folder_id', 'id')->withTrashed();
    }

    /**
     * Get all folders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function folders()
    {
        return $this->children()->with('folders');
    }

    /**
     * Get all trashed folders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trashed_folders()
    {
        return $this->children()->with('trashed_folders')->withTrashed()->select(['parent_id', 'id', 'name']);
    }

    /**
     * Get childrens
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id');
    }

    /**
     * Get trashed childrens
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trashed_children()
    {
        return $this->hasMany(Folder::class, 'parent_id', 'id')->withTrashed();
    }

    /**
     * Get sharing attributes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shared()
    {
        return $this->hasOne(Share::class, 'item_id', 'id');
    }

    // Delete all folder children
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });

        static::deleting(function ($item) {

            if ($item->isForceDeleting()) {

                $item->trashed_children()->each(function ($folder) {
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
            $item->trashed_children()->each(function ($folder) {
                $folder->restore();
            });

            // Restore children files
            $item->trashed_files()->each(function ($files) {
                $files->restore();
            });
        });
    }
}