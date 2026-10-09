<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Stripe\Actions;

use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Support\Services\StripeHttpClient;
use VueFileManager\Subscription\Support\Miscellaneous\Stripe\Exceptions\ChargeFailedException;

class ChargeFromSavedPaymentMethodAction
{
    use StripeHttpClient;

    /**
     * It gets stored payment method and charge custom amount of money from customer
     *
     * @throws ChargeFailedException
     */
    public function __invoke($user, $amount): array
    {
        // Get payment method
        $paymentMethodCode = $user->creditCards()->first()->reference;

        // Get plan currency
        $currency = $user->subscription->plan->currency;

        // Create payment intent
        $paymentIntent = $this->post('/payment_intents', [
            'amount'         => $amount * 100,
            'currency'       => strtolower($currency),
            'customer'       => $user->customerId('stripe'),
            'payment_method' => $paymentMethodCode,
            'off_session'    => 'true',
            'confirm'        => 'true',
        ]);

        // Transaction failed for some reason
        if (array_key_exists('error', $paymentIntent->json())) {
            throw new ChargeFailedException();
        }

        // Transaction succeeded
        return $paymentIntent->json();
    }
}
