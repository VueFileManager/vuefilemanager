<?php
namespace VueFileManager\Subscription\Domain\DunningEmails\Actions;

use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;

class SendDunningEmailToUserAction
{
    public function __invoke(
        Dunning $dunning
    ): void {
        // Get notification class
        $notification = config('subscription.notifications.DunningEmailToCoverAccountUsageNotification');

        // Send notification
        $dunning->user->notify(new $notification($dunning));

        // Update reminder count
        if ($dunning->sequence < 3) {
            $dunning->increment('sequence');
        }
    }
}
