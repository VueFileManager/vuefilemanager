<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageString extends Model
{
    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

    protected $fillable = ['value' ,'key', 'lang'];
    

}
