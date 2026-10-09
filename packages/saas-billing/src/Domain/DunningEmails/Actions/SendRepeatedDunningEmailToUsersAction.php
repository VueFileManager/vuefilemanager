<?php
namespace VueFileManager\Subscription\Domain\DunningEmails\Actions;

use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;

class SendRepeatedDunningEmailToUsersAction
{
    public function __construct(
        public SendDunningEmailToUserAction $sendDunningEmailToUser,
    ) {
    }

    public function __invoke(): void
    {
        Dunning::query()
            ->whereNot('sequence', 3)
            ->cursor()
            ->each(function ($dunning) {
                $diffInDays = $dunning->updated_at->diffInDays();

                // Send reminder for every 2 days
                if ($diffInDays !== 0 && $diffInDays % 2 == 0) {
                    ($this->sendDunningEmailToUser)($dunning);
                }
            });
    }
}
