<?php
namespace App\Users\Restrictions\Engines;

use App\Users\Models\User;
use App\Users\Restrictions\RestrictionsEngine;

class MeteredBillingRestrictionsEngine implements RestrictionsEngine
{
    public function canUpload(User $user, int $fileSize = 0): bool
    {
        // Check the count of the dunning emails
        if ($this->getDunningSequenceCount($user) === 3) {
            return false;
        }

        // Disable upload when user has more than 3 failed payments
        return $this->checkFailedPayments($user);
    }

    public function canDownload(User $user): bool
    {
        // Check the count of the dunning emails
        if ($this->getDunningSequenceCount($user) === 3) {
            return false;
        }

        // Disable download when user has more than 3 failed payments
        return $this->checkFailedPayments($user);
    }

    public function canCreateFolder(User $user): bool
    {
        // Check the count of the dunning emails
        if ($this->getDunningSequenceCount($user) === 3) {
            return false;
        }

        // Disable create folder when user has more than 3 failed payments
        return $this->checkFailedPayments($user);
    }

    public function canCreateTeamFolder(User $user): bool
    {
        // Check the count of the dunning emails
        if ($this->getDunningSequenceCount($user) === 3) {
            return false;
        }

        // Disable create folder when user has more than 3 failed payments
        return $this->checkFailedPayments($user);
    }

    public function canInviteTeamMembers(User $user, array $newInvites = []): bool
    {
        return true;
    }

    public function canVisitShared(User $user): bool
    {
        // Check the count of the dunning emails
        if ($this->getDunningSequenceCount($user) === 3) {
            return false;
        }

        // Disable share visit when user has more than 3 failed payments
        return $this->checkFailedPayments($user);
    }

    private function getDunningSequenceCount(User $user): int
    {
        return cache()->remember("dunning-count.$user->id", 3600, fn () => $user?->dunning->sequence ?? 0);
    }

    private function checkFailedPayments(User $user): bool
    {
        return cache()->remember("failed-payments-count.$user->id", 3600, fn () => !($user->failedPayments()->count() >= 3));
    }
}
