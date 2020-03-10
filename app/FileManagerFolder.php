<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use \Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class FileManagerFolder extends Model
{
    use Searchable, SoftDeletes, SoftCascadeTrait;

    protected $guarded = [
        'id'
    ];

    protected $softCascade = [
        'children', 'files'
    ];

    protected $appends = [
        'items', 'trashed_items'
    ];

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
        return Carbon::create($this->attributes['created_at'])->format('j M Y \a\t H:i');
    }

    /**
     * Format created at date reformat
     *
     * @return string
     */
    public function getDeletedAtAttribute()
    {
        if (! $this->attributes['deleted_at']) return null;

        return Carbon::create($this->attributes['deleted_at'])->format('j M Y \a\t H:i');
    }

    /**
     * Get parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\FileManagerFolder', 'parent_id', 'unique_id');
    }

    public function folderIds()
    {
        return $this->children()->with('folderIds')->select(['unique_id', 'parent_id']);
    }

    /**
     * Get all files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {

        return $this->hasMany('App\FileManagerFile', 'folder_id', 'unique_id');
    }

    /**
     * Get all files
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trashed_files()
    {

        return $this->hasMany('App\FileManagerFile', 'folder_id', 'unique_id')->withTrashed();
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

    public function trashed_folders()
    {
        return $this->children()->with('folders')->withTrashed()->select(['parent_id', 'unique_id']);
    }

    /**
     * Get childrens
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('App\FileManagerFolder', 'parent_id', 'unique_id');
    }

    // Delete all folder childrens
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {

            $item->children()->each(function($folder) {
                $folder->delete();
            });
        });

        static::restoring(function ($item) {

            $item->children()->each(function($folder) {
                $folder->restore();
            });
        });
    }
}
