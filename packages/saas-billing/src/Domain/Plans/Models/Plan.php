<?php
namespace VueFileManager\Subscription\Domain\Plans\Models;

use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\PlanFactory;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

/**
 * @method static create(array $array)
 * @property string id
 * @property string type
 * @property string name
 * @property string description
 * @property string currency
 * @property float amount
 * @property string interval
 * @property bool visible
 */
class Plan extends Model
{
    use HasFactory;
    use Sortable;

    protected $guarded = [];

    protected $casts = [
        'id'      => 'string',
        'visible' => 'bool',
        'amount'  => 'float',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function drivers(): HasMany
    {
        return $this->hasMany(PlanDriver::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function fixedFeatures(): HasMany
    {
        return $this->hasMany(PlanFixedFeature::class);
    }

    public function meteredFeatures(): HasMany
    {
        return $this->hasMany(PlanMeteredFeature::class);
    }

    /**
     * Get original driver id
     */
    public function driverId(string $driver)
    {
        return $this->drivers()
            ->where('driver', $driver)
            ->first()
            ->driver_plan_id ?? null;
    }

    protected static function newFactory(): PlanFactory
    {
        return PlanFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($plan) {
            $plan->id = Str::uuid();
        });

        static::updated(function () {
            cache()->add('action.synchronize-plans', now()->toString());
        });

        static::deleting(function ($plan) {
            $plan->fixedFeatures()->delete();
            $plan->meteredFeatures()->delete();
            $plan->drivers()->delete();
        });
    }
}
