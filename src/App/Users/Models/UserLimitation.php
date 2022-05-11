<?php
namespace App\Users\Models;

use DB;
use ByteUnits\Metric;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserLimitationFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int max_storage_amount
 * @property int max_team_members
 * @property string user_id
 */
class UserLimitation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'user_id', 'user',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function newFactory(): UserLimitationFactory
    {
        return UserLimitationFactory::new();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get summary of user limitations and their usage
     */
    public function summary(): array
    {
        return [
            'max_storage_amount' => $this->getMaxStorageAmount(),
            'max_team_members'   => $this->getMaxTeamMembers(),
        ];
    }

    /**
     * Get usage data of user storage
     */
    private function getMaxStorageAmount(): array
    {
        $userCapacity = $this->user->usedCapacity;

        return [
            'use'        => Metric::bytes($userCapacity)->format(),
            'total'      => toGigabytes($this->max_storage_amount),
            'percentage' => get_storage_percentage($userCapacity, $this->max_storage_amount),
        ];
    }

    /**
     * Get usage data of team members
     */
    private function getMaxTeamMembers(): array
    {
        $userTeamFolderIds = DB::table('team_folder_members')
            ->where('user_id', $this->user_id)
            ->pluck('parent_id');

        $memberIds = DB::table('team_folder_members')
            ->where('user_id', '!=', $this->user_id)
            ->whereIn('parent_id', $userTeamFolderIds)
            ->pluck('user_id')
            ->unique();

        // Get member emails
        $memberEmails = User::whereIn('id', $memberIds)
            ->pluck('email');

        // Get active invitation emails
        $InvitationEmails = DB::table('team_folder_invitations')
            ->where('status', 'pending')
            ->where('inviter_id', $this->user_id)
            ->pluck('email')
            ->unique();

        // Get allowed emails in the limit
        $totalUsedEmails = $memberEmails->merge($InvitationEmails)
            ->unique();

        // Get usage in percent
        $percentage = (int) $this->max_team_members === 0
            ? 100
            : ($totalUsedEmails->count() / $this->max_team_members) * 100;

        return [
            'use'        => $totalUsedEmails->count(),
            'total'      => (int) $this->max_team_members,
            'percentage' => $percentage,
            'meta'       => [
                'allowed_emails' => $totalUsedEmails,
            ],
        ];
    }
}
