<?php
namespace VueFileManager\Subscription\Domain\FailedPayments\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;
use VueFileManager\Subscription\Database\Factories\FailedPaymentFactory;

/**
 * @method static create(array $array)
 * @property Model user
 * @property string id
 * @property string user_id
 * @property string transaction_id
 * @property float amount
 * @property string currency
 * @property string source
 * @property string note
 * @property array metadata
 * @property int attempts
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class FailedPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id'       => 'string',
        'amount'   => 'float',
        'metadata' => 'array',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): FailedPaymentFactory
    {
        return FailedPaymentFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(config('auth.providers.users.model'), 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($failedPayment) => $failedPayment->id = Str::uuid());
    }
}
