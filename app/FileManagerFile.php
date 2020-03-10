<?php

namespace App;

use ByteUnits\Metric;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use TeamTNT\TNTSearch\Indexer\TNTIndexer;
use \Illuminate\Database\Eloquent\SoftDeletes;
use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

class FileManagerFile extends Model
{
    use Searchable, SoftDeletes, SoftCascadeTrait;


    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'file_url'
    ];

    /**
     * Format created at date
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::create($this->attributes['created_at'])->format('j M Y \a\t H:i');;
    }

    /**
     * Form\a\t created at date reformat
     *
     * @return string
     */
    public function getDeletedAtAttribute()
    {
        if (! $this->attributes['deleted_at']) return null;

        return Carbon::create($this->attributes['deleted_at'])->format('j M Y at H:i');
    }

    /**
     * Format filesize
     *
     * @param $value
     * @return string
     */
    public function getFilesizeAttribute()
    {
        return Metric::bytes($this->attributes['filesize'])->format();
    }

    /**
     * Format thumbnail url
     *
     * @param $value
     * @return string
     */
    public function getThumbnailAttribute()
    {
        return $this->attributes['thumbnail'] ? route('file', ['name' => $this->attributes['thumbnail']]) : null;
    }

    /**
     * Format file url
     *
     * @param $value
     * @return string
     */
    public function getFileUrlAttribute()
    {
        return route('file', ['name' => $this->attributes['basename']]);
    }

    /**
     * Index file
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
     * Get parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\FileManagerFolder', 'folder_id', 'unique_id');
    }

    /**
     * Get folder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function folder()
    {
        return $this->hasOne('App\FileManagerFolder', 'unique_id', 'folder_id');
    }
}
