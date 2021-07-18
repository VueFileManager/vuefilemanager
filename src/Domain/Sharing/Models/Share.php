<?php
namespace Domain\Sharing\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static whereNotNull(string $string)
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

    /**
     * Generate share link
     *
     * @return string
     */
    public function getLinkAttribute()
    {
        return url('/share', ['token' => $this->attributes['token']]);
    }

    public function user()
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
            $shared->token = Str::random(16);
        });
    }
}
