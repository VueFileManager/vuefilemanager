<?php
namespace VueFileManager\Subscription\Domain\Plans\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\PlanMeteredFeatureFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string plan_id
 * @property string key
 * @property string charge_by
 */
class PlanMeteredFeature extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id'    => 'string',
        'value' => 'integer',
    ];

    public $primaryKey = null;

    public $incrementing = false;

    public $timestamps = false;

    public function tiers(): HasMany
    {
        return $this->hasMany(MeteredTier::class, 'metered_feature_id', 'id');
    }

    protected static function newFactory(): PlanMeteredFeatureFactory
    {
        return PlanMeteredFeatureFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($meteredItem) {
            $meteredItem->id = Str::uuid();
        });

        static::deleting(fn ($plan) => $plan->tiers()->delete());
    }
}
