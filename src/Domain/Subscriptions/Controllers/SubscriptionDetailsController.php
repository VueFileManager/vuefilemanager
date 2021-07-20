<?php


namespace Domain\Subscriptions\Controllers;


use App\Http\Controllers\Controller;
use App\Users\Resources\UserSubscription;
use Auth;

class SubscriptionDetailsController extends Controller
{
    public function __invoke(): mixed
    {
        $user = Auth::user();

        if (!$user->subscription('main')) {
            return abort(204, "User don't have any subscription");
        }

        $slug = "subscription-user-{$user->id}";

        if (cache()->has($slug)) {
            return cache()->get($slug);
        }

        return cache()->rememberForever(
            $slug, fn() => new UserSubscription($user)
        );
    }
}