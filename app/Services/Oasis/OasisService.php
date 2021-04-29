<?php
namespace App\Services\Oasis;

use Carbon\Carbon;
use App\Services\StripeService;
use App\Models\Oasis\SubscriptionRequest;
use App\Notifications\Oasis\ReminderForPaymentRequiredNotification;

class OasisService
{
    /**
     * Get requested subscription requests and remind via
     * email to activate order
     */
    public function order_reminder()
    {
        SubscriptionRequest::whereStatus('requested')
            ->get()
            ->each(function ($request) {
                // Get diffInHours
                $diff = Carbon::parse($request->created_at)
                    ->diffInHours(Carbon::now());

                // Send order reminder
                if ($diff == 8) {
                    $plan = resolve(StripeService::class)
                        ->getPlan($request->requested_plan);

                    $request->user->notify(new ReminderForPaymentRequiredNotification(
                        $request,
                        $plan
                    ));
                }
            });
    }
}
