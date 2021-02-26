<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Zip extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
