<?php
namespace App\Users\Restrictions\Engines;

use App\Users\Models\User;
use Illuminate\Support\Facades\Schema;
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

    public function getRestrictionReason(User $user): string|null
    {
        if ($this->getDunningSequenceCount($user) === 3) {
            return match ($user->dunning->type) {
                'limit_usage_in_new_accounts' => 'Please make your first payment to cover your usage.',
                'usage_bigger_than_balance' => 'Please increase your account balance higher than your monthly usage.',
            };
        }

        if (! $this->checkFailedPayments($user)) {
            return 'Please update your credit card to pay your usage.';
        }

        return null;
    }

    private function getDunningSequenceCount(User $user): int
    {
        if (Schema::hasTable('dunnings')) {
            return cache()->remember("dunning-count.$user->id", 3600, fn () => $user?->dunning->sequence ?? 0);
        }

        return 0;
    }

    private function checkFailedPayments(User $user): bool
    {
        return cache()->remember("failed-payments-count.$user->id", 3600, fn () => !($user->failedPayments()->count() >= 3));
    }
}
