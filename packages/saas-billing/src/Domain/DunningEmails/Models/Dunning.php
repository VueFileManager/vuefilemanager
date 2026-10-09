<?php
namespace VueFileManager\Subscription\Domain\DunningEmails\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use VueFileManager\Subscription\Database\Factories\DunningFactory;
use VueFileManager\Subscription\Domain\DunningEmails\Actions\SendDunningEmailToUserAction;

/**
 * @method static create(array $array)
 * @method static where(string $key, string $value)
 * @property string id
 * @property string user_id
 * @property int sequence
 * @property string type
 */
class Dunning extends Model
{
    use HasFactory;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];

    protected static function newFactory(): DunningFactory
    {
        return DunningFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(config('auth.providers.users.model'), 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dunning) {
            $dunning->id = Str::uuid();
            $dunning->sequence = 0;
        });

        // Send first dunning email after creating dunning record
        static::created(fn ($dunning) => resolve(SendDunningEmailToUserAction::class)($dunning));
    }
}
