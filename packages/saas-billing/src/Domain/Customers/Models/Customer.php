<?php
namespace VueFileManager\Subscription\Domain\Customers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\CustomerFactory;

/**
 * @method static create(array $array)
 * @method static where(string $key, string $value)
 * @property string user_id
 * @property string driver
 * @property string driver_user_id
 */
class Customer extends Model
{
    use HasFactory;

    public $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];

    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(config('auth.providers.users.model'), 'id', 'user_id');
    }
}
