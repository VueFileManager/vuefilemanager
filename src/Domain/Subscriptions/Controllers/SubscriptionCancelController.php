<?php


namespace Domain\Subscriptions\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SubscriptionCancelController extends Controller
{
    /**
     * Cancel Subscription
     */
    public function __invoke(): Response
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo_account($user->email)) {
            return response('Done!', 204);
        }

        // Cancel subscription
        $user->subscription('main')->cancel();

        // Forget user subscription
        Cache::forget("subscription-user-{$user->id}");

        return response('Done!', 204);
    }
}