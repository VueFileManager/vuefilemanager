<?php
namespace VueFileManager\Subscription\Domain\Credits\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CreditUserBalanceController extends Controller
{
    public function __invoke(Request $request, $id): JsonResponse
    {
        $response = [
            'type'    => 'success',
            'message' => 'User balance was successfully increased',
        ];

        $user = config('auth.providers.users.model')::find($id);

        // Abort in demo mode
        if ($user->email === 'howdy@hi5ve.digital') {
            return response()->json($response);
        }

        // Credit user balance
        $user->creditBalance($request->input('amount'));

        // Store transaction
        $user->transactions()->create([
            'status'   => 'completed',
            'type'     => 'credit',
            'driver'   => 'system',
            'note'     => __('Bonus'),
            'currency' => $user->balance->currency,
            'amount'   => $request->input('amount'),
        ]);

        // Send user bonus notification
        $bonus = format_currency($request->input('amount'), $user->balance->currency);

        // Get notification
        $BonusCreditAddedNotification = config('subscription.notifications.BonusCreditAddedNotification');

        // Notify user
        $user->notify(new $BonusCreditAddedNotification($bonus));

        return response()->json($response);
    }
}
