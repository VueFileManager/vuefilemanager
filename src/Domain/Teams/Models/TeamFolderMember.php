<?php
namespace Domain\Teams\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\TeamFolderMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static create(array $array)
 * @property string id
 * @property string parent_id
 * @property string email
 * @property string status
 * @property string created_at
 * @property string updated_at
 */
class TeamFolderMember extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    protected static function newFactory(): TeamFolderMemberFactory
    {
        return TeamFolderMemberFactory::new();
    }
}
