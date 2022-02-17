<?php
namespace Domain\UploadRequest\Models;

use App\Users\Models\User;
use Database\Factories\UploadRequestFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string user_id
 * @property string folder_id
 * @property string status
 * @property string email
 * @property string notes
 * @property string created_at
 * @property string updated_at
 */
class UploadRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): UploadRequestFactory
    {
        return UploadRequestFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(fn ($invitation) => $invitation->id = Str::uuid());
    }
}
