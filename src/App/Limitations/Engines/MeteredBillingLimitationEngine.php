<?php
namespace App\Limitations\Engines;

use App\Users\Models\User;
use App\Limitations\LimitationEngine;

class MeteredBillingLimitationEngine implements LimitationEngine
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
}
