<?php


namespace App\Services;


use App\PaymentGateway;
use App\User;
use App\UserCard;
use App\UserSettings;
use Cartalyst\Stripe\Exception\CardErrorException;
use Stripe;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentService
{
    /**
     * PaymentService constructor.
     */
    public function __construct()
    {
        $this->stripe = Stripe::make(config('services.stripe.secret'), '2020-03-02');
    }

    /**
     * Get and charge customer
     *
     * @param $request
     * @param $plan
     * @param $user
     */
    public function getAndChargeCustomer($request, $plan, $user)
    {
        // Get Stripe Customer
        $customer = $this->findOrCreateStripeCustomer($user);

        $paymentIntent = $this->stripe->paymentIntents()->create([
            'amount'               => $plan->price,
            'currency'             => 'USD',
            'payment_method_types' => [
                'card',
            ],
        ]);

        dd($paymentIntent);

        // Try charge customer
        try {
            if ($request->has('payment.meta.card_token')) {

                // Register customer credit card
                $created_card = $this->registerStripeCreditCard($customer, $request->input('payment.meta.card_token'));

                // Charge with created credit card
                /*$this->stripe->charges()->create([
                    'description' => 'Subscription ' . $plan->name,
                    'customer'    => $customer['id'],
                    'amount'      => $plan->price,
                    'currency'    => 'USD',
                    'source'      => $created_card->card_id
                ]);*/

            } else {

                // Charge with customer default creadit card
                $this->stripe->charges()->create([
                    'description' => 'Subscription ' . $plan->name,
                    'customer'    => $customer['id'],
                    'amount'      => $plan->price,
                    'currency'    => 'USD',
                ]);
            }

        } catch (CardErrorException $error) {

            //dd($error);

            // Remove previously registered card
            if (in_array($error->getErrorCode(), ['rejected_card', 'card_declined'])
                && $request->has('payment.meta.card_token')
                && isset($error->getRawOutput()['error']['charge'])) {

                // Get charge
                $charge = $this->stripe->charges()->find($error->getRawOutput()['error']['charge']);

                // Remove card from stripe
                $this->deleteStripeCard($user->settings->stripe_customer_id, $charge['payment_method']);

                // Get card
                $error_card = UserCard::where('card_id', $charge['payment_method'])->first();

                // Delete card
                $error_card->delete();
            }

            throw new HttpException($error->getCode(), $error->getMessage());
        }

        // Increase payment processed column
        PaymentGateway::where('slug', 'stripe')->first()->increment('payment_processed');
    }


    /**
     * Find or create stripe customer
     *
     * @param $user
     * @return array
     */
    private function findOrCreateStripeCustomer($user)
    {
        // Get existing stripe customer
        if ($user->settings->stripe_customer_id) {

            return $this->stripe->customers()->find($user->settings->stripe_customer_id);
        }

        // Create new stripe costumer
        if (!$user->settings->stripe_customer_id) {

            $customer = $this->stripe->customers()->create([
                'email'   => $user->email,
                'name'    => $user->name,
                'address' => [
                    'city'        => $user->settings->billing_city,
                    'country'     => $user->settings->billing_country,
                    'line1'       => $user->settings->billing_address,
                    'state'       => $user->settings->billing_state,
                    'postal_code' => $user->settings->billing_postal_code,
                ]
            ]);

            // Store user stripe_customer_id
            $user->settings()->update([
                'stripe_customer_id' => $customer['id']
            ]);

            return $customer;
        }
    }

    /**
     * Register stripe credit card
     *
     * @param $customer
     * @param $card_id
     * @return object
     */
    private function registerStripeCreditCard($customer, $card_id)
    {
        // Register user card
        $card = $this->stripe->cards()->create($customer['id'], $card_id);

        // Get user settings
        $user_settings = UserSettings::where('stripe_customer_id', $customer['id'])->first();

        // Set default status
        $default_card = UserCard::where('user_id', $user_settings->user_id)->get()->count() > 0 ? 0 : 1;

        // Store user card
        $card = UserCard::create([
            'user_id'   => $user_settings->user_id,
            'status'    => 'active',
            'default'   => $default_card,
            'provider'  => 'stripe',
            'card_id'   => $card['id'],
            'brand'     => $card['brand'],
            'last4'     => $card['last4'],
            'exp_month' => $card['exp_month'],
            'exp_year'  => $card['exp_year'],
        ]);

        return $card;
    }

    /**
     * Set new default source from existing credit card
     *
     * @param $stripe_customer_id
     * @param $card_id
     */
    public function setDefaultStripeCard($stripe_customer_id, $card_id)
    {
        $this->stripe->customers()->update($stripe_customer_id, [
            'default_source' => $card_id,
        ]);
    }

    /**
     * Delete customer stripe credit card
     *
     * @param $stripe_customer_id
     * @param $card_id
     */
    public function deleteStripeCard($stripe_customer_id, $card_id)
    {
        $this->stripe->cards()->delete($stripe_customer_id, $card_id);
    }
}