<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;

class SwapSubscriptionController extends Controller
{
    public function __invoke(
        Plan $plan
    ): JsonResponse {
        if (is_demo_account()) {
            return response()->json([
                'type'    => 'success',
                'message' => 'Subscription was swapped successfully',
            ]);
        }

        $response = auth()
            ->user()
            ->subscription
            ->swap($plan)
            ->json();

        // Swap existing user subscription
        return response()->json($response);
    }
}
