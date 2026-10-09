<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Controllers;

use Illuminate\Http\JsonResponse;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;

class GetSubscriptionController
{
    public function __invoke(): JsonResponse
    {
        // Get subscription
        $subscription = auth()->user()->subscription;

        if ($subscription) {
            return response()->json(new SubscriptionResource($subscription));
        }

        return response()->json([
            'type'    => 'error',
            'message' => 'User do not have subscription',
        ], 404);
    }
}
