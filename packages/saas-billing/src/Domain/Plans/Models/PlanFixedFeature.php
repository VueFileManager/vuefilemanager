<?php
namespace VueFileManager\Subscription\Domain\Plans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\PlanFixedFeatureFactory;

/**
 * @method static create(array $array)
 * @property string plan_id
 * @property string key
 * @property string value
 */
class PlanFixedFeature extends Model
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

    protected static function newFactory(): PlanFixedFeatureFactory
    {
        return PlanFixedFeatureFactory::new();
    }
}
