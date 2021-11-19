<?php
namespace App\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserLimitationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLimitation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'user_id',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): UserLimitationFactory
    {
        return UserLimitationFactory::new();
    }
}
