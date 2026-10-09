<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;

class CancelSubscriptionController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = auth()->user();

        // Cancel existing user subscription
        $user->subscription->cancel();

        return response()->json(new SubscriptionResource($user->subscription));
    }
}
