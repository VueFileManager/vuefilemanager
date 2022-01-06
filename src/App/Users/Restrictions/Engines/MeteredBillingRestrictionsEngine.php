<?php
namespace App\Users\Restrictions\Engines;

use App\Users\Models\User;
use App\Users\Restrictions\RestrictionsEngine;

class MeteredBillingRestrictionsEngine implements RestrictionsEngine
{
    public function canUpload(User $user, int $fileSize = 0): bool
    {
        // Disable upload when user has more than 3 failed payments
        return ! ($user->failedPayments()->count() >= 3);
    }

    public function canDownload(User $user): bool
    {
        // Disable download when user has more than 3 failed payments
        return ! ($user->failedPayments()->count() >= 3);
    }

    public function canCreateFolder(User $user): bool
    {
        // Disable create folder when user has more than 3 failed payments
        return ! ($user->failedPayments()->count() >= 3);
    }

    public function canCreateTeamFolder(User $user): bool
    {
        // Disable create folder when user has more than 3 failed payments
        return ! ($user->failedPayments()->count() >= 3);
    }

    public function canInviteTeamMembers(User $user, array $newInvites = []): bool
    {
        return true;
    }

    public function canVisitShared(User $user): bool
    {
        // Disable share visit when user has more than 3 failed payments
        return ! ($user->failedPayments()->count() >= 3);
    }
}
