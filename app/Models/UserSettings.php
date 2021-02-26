<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    public $timestamps = false;

    protected $guarded = [
        'id',
        'storage_capacity'
    ];
}
