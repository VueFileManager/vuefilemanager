<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguageTranslation extends Model
{
    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'value'
    ];
}
