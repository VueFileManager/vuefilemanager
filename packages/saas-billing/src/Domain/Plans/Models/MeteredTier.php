<?php
namespace VueFileManager\Subscription\Domain\Plans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\MeteredTierFactory;

/**
 * @method static create(array $array)
 * @property string metered_feature_id
 * @property int first_unit
 * @property int last_unit
 * @property float per_unit
 * @property float flat_fee
 */
class MeteredTier extends Model
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

    protected static function newFactory(): MeteredTierFactory
    {
        return MeteredTierFactory::new();
    }
}
