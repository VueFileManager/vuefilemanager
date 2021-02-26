<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Traffic extends Model
{
    protected $fillable = [
        'user_id',
        'upload',
        'download'
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }
}
