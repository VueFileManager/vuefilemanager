<?php
namespace App\Users\Restrictions\Engines;

use App\Users\Models\User;
use App\Users\Restrictions\RestrictionsEngine;
use Domain\Teams\Actions\CheckMaxTeamMembersLimitAction;

class FixedBillingRestrictionsEngine implements RestrictionsEngine
{
    public function canUpload(User $user, int $fileSize = 0): bool
    {
        // Get used capacity
        $usedPercentage = get_storage_percentage(
            used: $user->usedCapacity + $fileSize,
            maxAmount: $user->limitations->max_storage_amount,
        );

        // Check if storage usage exceed predefined capacity
        return ! ($usedPercentage >= 100);
    }

    public function canDownload(User $user): bool
    {
        return true;
    }

    public function canCreateFolder(User $user): bool
    {
        return true;
    }

    public function canCreateTeamFolder(User $user): bool
    {
        return true;
    }

    public function canInviteTeamMembers(User $user, array $newInvites = []): bool
    {
        return resolve(CheckMaxTeamMembersLimitAction::class)($user, $newInvites);
    }

    public function canVisitShared(User $user): bool
    {
        return true;
    }

    public function getRestrictionReason(User $user): string|null
    {
        return null;
    }
}
