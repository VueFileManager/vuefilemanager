<?php
namespace App\Users\Restrictions\Engines;

use App\Users\Models\User;
use App\Users\Restrictions\RestrictionsEngine;

class DefaultRestrictionsEngine implements RestrictionsEngine
{
    public function canUpload(User $user, int $fileSize = 0): bool
    {
        // 1. If storage limitations is set to false, then allow upload
        if (! get_settings('storage_limitation')) {
            return true;
        }

        // Get used storage percentage
        $usedPercentage = get_storage_percentage(
            used: $user->usedCapacity + $fileSize,
            maxAmount: $user->limitations->max_storage_amount,
        );

        // 2. Check if storage usage exceed predefined capacity
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
        return true;
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
