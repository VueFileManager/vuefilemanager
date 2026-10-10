<?php
namespace VueFileManager\Subscription\Domain\Plans\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string plan_id
 * @property string driver_plan_id
 * @property string driver
 * @method static where(string $key, string $value)
 */
class PlanDriver extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public $primaryKey = null;

    public $incrementing = false;

    public function plan(): HasOne
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
}
