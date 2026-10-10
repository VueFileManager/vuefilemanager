<?php
namespace VueFileManager\Subscription\Domain\BillingAlerts\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\BillingAlerts\Requests\StoreBillingAlertRequest;
use VueFileManager\Subscription\Domain\BillingAlerts\Requests\UpdateBillingAlertRequest;

class BillingAlertController extends Controller
{
    public function store(
        StoreBillingAlertRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'Billing alert was stored successfully',
        ];

        if (is_demo_account()) {
            return response()->json($message, 201);
        }

        // Check if billing alert exists
        if ($request->user()->billingAlert()->exists()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'You already have created billing alert.',
            ], 422);
        }

        // Create new billing alert
        $request
            ->user()
            ->billingAlert()
            ->create([
                'amount' => $request->input('amount'),
            ]);

        return response()->json($message, 201);
    }

    public function update(
        UpdateBillingAlertRequest $request,
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'Billing alert was updated successfully',
        ];

        if (is_demo_account()) {
            return response()->json($message);
        }

        $request->user()->billingAlert->update([
            'amount'    => $request->input('amount'),
            'triggered' => false,
        ]);

        return response()->json($message);
    }

    public function destroy(): JsonResponse
    {
        $message = [
            'type'    => 'success',
            'message' => 'Billing alert was deleted successfully',
        ];

        if (is_demo_account()) {
            return response()->json($message);
        }

        request()->user()->billingAlert->delete();

        return response()->json($message);
    }
}
