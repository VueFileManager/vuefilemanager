<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\SubscriptionDriverFactory;

/**
 * @property string subscription_id
 * @property string driver_subscription_id
 * @property string driver
 * @method static where(string $key, string $value)
 */
class SubscriptionDriver extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

    protected static function newFactory(): SubscriptionDriverFactory
    {
        return SubscriptionDriverFactory::new();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }
}
