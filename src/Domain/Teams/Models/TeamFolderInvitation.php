<?php
namespace Domain\Teams\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\TeamFolderInvitationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string folder_id
 * @property string email
 * @property string status
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

    protected static function newFactory(): TeamFolderInvitationFactory
    {
        return TeamFolderInvitationFactory::new();
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
