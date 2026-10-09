<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Models;

use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Usage\Models\Usage;
use VueFileManager\Subscription\Database\Factories\SubscriptionFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string user_id
 * @property string plan_id
 * @property string type
 * @property string name
 * @property string subscription_id
 * @property DateTime renews_at
 * @property DateTime trial_ends_at
 * @property DateTime ends_at
 */
class Subscription extends Model
{
    use Sortable;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id'            => 'string',
        'plan_id'       => 'string',
        'user_id'       => 'string',
        'ends_at'       => 'datetime',
        'trial_ends_at' => 'datetime',
        'renews_at'     => 'datetime',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): SubscriptionFactory
    {
        return SubscriptionFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(config('auth.providers.users.model'), 'id', 'user_id');
    }

    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(Usage::class, 'subscription_id', 'id');
    }

    public function driver(): hasOne
    {
        return $this->hasOne(SubscriptionDriver::class);
    }

    /**
     * Get subscription driver
     */
    protected function gateway(): EngineManager
    {
        return resolve(EngineManager::class);
    }

    /**
     * Cancel subscription
     */
    public function cancel()
    {
        $this->gateway()
            ->driver($this->driver->driver)
            ->cancelSubscription($this);
    }

    /**
     * Swap subscription
     */
    public function swap(Plan $plan)
    {
        return $this->gateway()
            ->driver($this->driver->driver)
            ->swapSubscription($this, $plan);
    }

    /**
     * Generate link for subscription detail update right in payment gateway
     */
    public function generateUpdateLink()
    {
        return $this->gateway()
            ->driver($this->driver->driver)
            ->updateSubscription($this, $this->plan ?? null);
    }

    /**
     * Get gateway subscription id
     */
    public function driverId(): string
    {
        return $this->driver->driver_subscription_id;
    }

    /**
     * Get gateway subscription id
     */
    public function driverName(): null|string
    {
        return $this->driver->driver ?? null;
    }

    /**
     * Check if subscription is on grace period
     */
    public function onGracePeriod(): bool
    {
        return $this->ends_at && $this->ends_at->isFuture();
    }

    /**
     * Check if subscription ended
     */
    public function ended(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    /**
     * Check if subscription is active
     */
    public function active(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if subscription is cancelled
     */
    public function cancelled(): bool
    {
        return ! is_null($this->ends_at);
    }

    /**
     * Get all subscription plan features
     */
    public function fixedFeatures()
    {
        return $this->plan->fixedFeatures()->pluck('value', 'key');
    }

    /**
     * Get single subscription plan feature by name
     */
    public function fixedFeature(string $feature)
    {
        return $this->plan->fixedFeatures()->where('key', $feature)->first()->value;
    }

    /**
     * Store subscription feature usage
     */
    public function recordUsage($key, $quantity): void
    {
        $meteredItem = $this->plan
            ->meteredFeatures()
            ->where('key', $key)
            ->first();

        $this->usages()->create([
            'metered_feature_id'   => $meteredItem->id,
            'quantity'             => $quantity,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscription) {
            $subscription->id = Str::uuid();
        });

        static::deleting(function ($subscription) {
            $subscription->driver()->delete();
        });
    }
}
