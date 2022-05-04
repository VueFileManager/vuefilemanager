<?php
namespace Domain\Teams\Models;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Database\Factories\TeamFolderInvitationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string parent_id
 * @property string email
 * @property string status
 * @property string permission
 * @property string created_at
 * @property string updated_at
 */
class TeamFolderInvitation extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'string',
    ];

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function accept()
    {
        $this->update([
            'status' => 'accepted',
        ]);
    }

    public function reject()
    {
        $this->update([
            'status' => 'rejected',
        ]);
    }

    protected static function newFactory(): TeamFolderInvitationFactory
    {
        return TeamFolderInvitationFactory::new();
    }

    public function inviter(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'inviter_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->id = Str::uuid();
            $invitation->color = config('vuefilemanager.colors')[rand(0, 4)];
        });
    }
}
