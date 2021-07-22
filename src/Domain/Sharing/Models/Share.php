<?php
namespace Domain\Sharing\Models;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Database\Factories\ShareFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereNotNull(string $string)
 * @method static where(string $string, string $token)
 * @property string user_id
 * @property mixed is_protected
 * @property string token
 * @property string item_id
 * @property string type
 * @property string password
 * @property User user
 */
class Share extends Model
{
    use Notifiable, HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['link'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'token';

    protected $casts = [
        'is_protected' => 'boolean',
    ];

    protected static function newFactory(): ShareFactory
    {
        return ShareFactory::new();
    }

    public function getLinkAttribute(): string
    {
        return url("/share/{$this->token}");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shared) {
            $shared->id = (string) Str::uuid();
            $shared->token = Str::random();
        });
    }
}
