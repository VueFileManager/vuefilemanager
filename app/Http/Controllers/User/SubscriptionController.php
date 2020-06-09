<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Invoice;
use Auth;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Upgrade account to subscription
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function upgrade(Request $request)
    {
        // TODO: validation request

        // Get user
        $user = Auth::user();

        // Get requested plan
        $plan = app('rinvex.subscriptions.plan')
            ->find($request->input('plan.data.id'));

        // Check if user have subscription
        if ($user->activeSubscriptions()->count() !== 0) {

            // Get old subscription
            $subscription = $user->subscription('main');

            // Change subscription plan
            $subscription->changePlan($plan);

        } else {

            // Create subscription
            $user->newSubscription('main', $plan);
        }

        // Update user storage limit
        $user->settings()->update([
            'storage_capacity' => $plan->features->first()->value
        ]);

        // Store invoice
        Invoice::create(
            get_invoice_data($user, $plan)
        );

        return response('Done!', 204);
    }

    /**
     * Cancel Subscription
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function cancel() {

        // Get user
        $user = Auth::user();

        // Cancel subscription
        $user->subscription('main')->cancel();

        return response('Done!', 204);
    }
}
