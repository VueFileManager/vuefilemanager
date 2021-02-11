<?php

namespace App;

use App\LanguageString;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected $guarded = ['id'];

    protected $keyType = 'string';

    public $incrementing = false ;

    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function($language) {
            $language->id = Str::uuid();
        });
    }

    public function languegeStrings()
    {
        return $this->hasMany('App\LanguageString', 'language_id', 'id');
    }
}
