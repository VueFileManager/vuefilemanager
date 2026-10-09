<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Controllers;

use Illuminate\Http\JsonResponse;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionResource;

class GetUserSubscriptionController
{
    public function __invoke($id): JsonResponse
    {
        $subscription = config('auth.providers.users.model')::find($id)
            ->subscription;

        if ($subscription) {
            return response()->json(new SubscriptionResource($subscription));
        }

        return response()->json([
            'type'    => 'error',
            'message' => 'User does not have subscription',
        ], 404);
    }
}
