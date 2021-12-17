<?php
namespace Support\Scheduler\Actions;

use DB;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class ReportUsageAction
{
    public function __invoke()
    {
        Subscription::whereIn('type', ['pre-paid', 'auto-renew'])
            ->where('status', 'active')
            ->cursor()
            ->each(function ($subscription) {
                $this->recordBandwidth($subscription);
                $this->recordStorageCapacity($subscription);
            });
    }

    private function recordStorageCapacity(Subscription $subscription): void
    {
        // Sum all file size
        $filesize = DB::table('files')
            ->where('user_id', $subscription->user->id)
            ->sum('filesize');

        // We count storage size in GB, e.g. 0.15 is 150mb
        $amount = $filesize / 1000000000;

        // Record storage capacity usage
        $subscription->recordUsage('storage', $amount);
    }

    private function recordBandwidth(Subscription $subscription): void
    {
        // We count storage size in GB, e.g. 0.15 is 150mb
        $record = $subscription
            ->user
            ->traffics()
            ->where('created_at', today()->subDay())
            ->first();

        $amount = ($record->download ?? 0) / 1000000000;

        // Record storage capacity usage
        $subscription->recordUsage('bandwidth', $amount);
    }
}
