<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class LanguageString extends Model
{
    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['value' ,'key', 'lang'];
    
    protected static function boot()
    {
        parent::boot();

        static::updated(function() {
            dd('test');
            // Cache::forget('language_strings');
        });
    }

}
