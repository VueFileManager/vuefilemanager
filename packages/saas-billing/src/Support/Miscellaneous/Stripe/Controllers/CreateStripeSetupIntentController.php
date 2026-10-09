<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Stripe\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Support\Services\StripeHttpClient;

class CreateStripeSetupIntentController extends Controller
{
    use StripeHttpClient;

    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Get or create a customer
        $customerId = $user->customerId('stripe') ?? $this->createCustomer($user);

        // Create setup intent
        $paymentIntent = $this->post('/setup_intents', [
            'customer'             => $customerId,
            'payment_method_types' => [
                'card',
            ],
        ]);

        // Return error response if request failed
        if ($paymentIntent->failed()) {
            abort(
                response()->json([
                    'type'    => 'setup-intent-creation-error',
                    'title'   => "Your setup intent couldn't be created",
                    'message' => $paymentIntent->json()['error']['message'],
                ], 500)
            );
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'Setup intent was created successfully',
            'data'    => [
                'client_secret' => $paymentIntent->json()['client_secret'],
            ],
        ], 201);
    }

    private function createCustomer($user)
    {
        $customer = resolve(EngineManager::class)
            ->driver('stripe')
            ->createCustomer([
                'id'      => $user->id,
                'email'   => $user->email,
                'name'    => $user->settings->first_name ?? null,
                'surname' => $user->settings->last_name ?? null,
            ]);

        return $customer->json()['id'];
    }
}
