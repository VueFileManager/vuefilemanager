<?php
namespace Domain\Zip\Models;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Database\Factories\ZipFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string basename
 * @property string shared_token
 * @property string id
 * @property string user_id
 * @property string created_at
 * @property string updated_at
 * @method static where(string $string, string $string1, string $toDateTimeString)
 */
class Zip extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): ZipFactory
    {
        return ZipFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
