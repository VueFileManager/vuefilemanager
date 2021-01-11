<?php

namespace App;

use App\LanguageString;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public function languegeStrings()
    {
        return $this->hasMany('App\LanguageString', 'language_id', 'id');
    }
}
