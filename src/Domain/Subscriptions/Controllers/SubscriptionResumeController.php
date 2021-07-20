<?php


namespace Domain\Subscriptions\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SubscriptionResumeController extends Controller
{
    /**
     * Resume Subscription
     */
    public function __invoke(): Response
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo_account($user->email)) {
            return response('Done!', 204);
        }

        // Resume subscription
        $user->subscription('main')->resume();

        // Forget user subscription
        Cache::forget("subscription-user-{$user->id}");

        return response('Done!', 204);
    }
}