<?php
namespace VueFileManager\Subscription\App\Scheduler;

use VueFileManager\Subscription\Domain\BillingAlerts\Models\BillingAlert;
use VueFileManager\Subscription\Domain\Usage\Actions\SumUsageForCurrentPeriodAction;

class CheckAndTriggerBillingAlertsSchedule
{
    public function __construct(
        public SumUsageForCurrentPeriodAction $sumUsageForCurrentPeriod
    ) {
    }

    public function __invoke()
    {
        BillingAlert::where('triggered', false)
            ->cursor()
            ->each(function ($alert) {
                // Get usage estimates
                $usageEstimates = ($this->sumUsageForCurrentPeriod)($alert->user->subscription);

                if ($usageEstimates->sum('amount') > $alert->amount) {
                    $alert->update([
                        'triggered' => true,
                    ]);

                    // Get notification
                    $BillingAlertTriggeredNotification = config('subscription.notifications.BillingAlertTriggeredNotification');

                    // Notify user
                    $alert->user->notify(new $BillingAlertTriggeredNotification());
                }
            });
    }
}
