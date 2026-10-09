<?php
namespace VueFileManager\Subscription\Domain\CreditCards\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\CreditCardFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string user_id
 * @property string brand
 * @property string last4
 * @property string service
 * @property string reference
 * @property bool is_expired
 * @property bool is_before_expiration
 * @property Carbon expiration
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class CreditCard extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id'         => 'string',
        'expiration' => 'datetime',
    ];

    protected $appends = [
        'is_expired', 'is_before_expiration',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiration->isPast();
    }

    public function getIsBeforeExpirationAttribute(): bool
    {
        $diffInDays = $this->expiration->diffInDays(now());

        return $this->expiration->isfuture() && $diffInDays <= 30;
    }

    protected static function newFactory(): CreditCardFactory
    {
        return CreditCardFactory::new();
    }

    public function user(): HasMany
    {
        return $this->hasMany(config('auth.providers.users.model'), 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($card) => $card->id = Str::uuid());
    }
}
