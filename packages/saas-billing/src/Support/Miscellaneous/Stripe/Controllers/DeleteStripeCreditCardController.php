<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Stripe\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Support\Services\StripeHttpClient;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;

class DeleteStripeCreditCardController extends Controller
{
    use StripeHttpClient;

    public function __invoke(
        Request $request,
        CreditCard $creditCard,
    ): JsonResponse {
        $message = [
            'status'  => 'success',
            'message' => 'Your credit card was successfully deleted',
        ];

        if (is_demo_account()) {
            return response()->json($message);
        }

        // Detach credit card from stripe
        $this->post("/payment_methods/{$creditCard->reference}/detach", []);

        // Delete credit card from database
        $creditCard->delete();

        return response()->json($message);
    }
}
