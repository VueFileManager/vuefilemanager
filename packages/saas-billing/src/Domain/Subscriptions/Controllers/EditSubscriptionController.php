<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class EditSubscriptionController extends Controller
{
    public function __invoke(
        Subscription $subscription
    ): JsonResponse {
        return response()->json($subscription->generateUpdateLink(), 201);
    }
}
