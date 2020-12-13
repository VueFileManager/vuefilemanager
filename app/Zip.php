<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Zip extends Model
{
    protected $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Generate uuid
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)Str::uuid();
        });
    }
}
