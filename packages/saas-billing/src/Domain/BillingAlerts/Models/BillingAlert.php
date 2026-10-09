<?php
namespace VueFileManager\Subscription\Domain\BillingAlerts\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string user_id
 * @property float amount
 * @property bool triggered
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class BillingAlert extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id'      => 'string',
        'amount'  => 'float',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function user(): HasOne
    {
        return $this->hasOne(config('auth.providers.users.model'), 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($alert) => $alert->id = Str::uuid());
    }
}
