<?php
namespace App\Users\Models;

use Illuminate\Database\Eloquent\Model;

class UserLimitation extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'user_id',
    ];

    public $incrementing = false;

    protected $keyType = 'string';
}
