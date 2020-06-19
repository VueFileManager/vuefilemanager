<?php


namespace App\Services;

use Illuminate\Support\Str;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StripeService
{
    /**
     * Stripe Service constructor.
     */
    public function __construct()
    {
        $this->stripe = Stripe::make(env('STRIPE_SECRET'), '2020-03-02');
    }

    /**
     * Get default payment option or set new default payment
     *
     * @param $request
     * @param $user
     * @return mixed
     */
    public function getOrSetDefaultPaymentMethod($request, $user)
    {
        // Check payment method
        if (! $request->has('payment.meta.pm') && $user->hasDefaultPaymentMethod()) {

            // Get default payment
            return $user->defaultPaymentMethod()->paymentMethod;

        } else if ( $request->has('payment.meta.pm') && $user->hasDefaultPaymentMethod() ) {

            // Set new payment
            return $user->addPaymentMethod($request->input('payment.meta.pm'))->paymentMethod;

        }  else if ( $request->has('payment.meta.pm') && ! $user->hasDefaultPaymentMethod() ) {

            // Set new payment
            return $user->updateDefaultPaymentMethod($request->input('payment.meta.pm'))->paymentMethod;

        } else {

            throw new HttpException(400, 'Something went wrong.');
        }
    }

    /**
     * Create new subscription or replace by new subscription
     *
     * @param $request
     * @param $user
     * @param $paymentMethod
     */
    public function createOrReplaceSubscription($request, $user)
    {
        try {

            // Get payment method
            $paymentMethod = $this->getOrSetDefaultPaymentMethod($request, $user);

            // Check if user have subscription
            if ($user->subscribed('main')) {

                // Change subscription plan
                $user->subscription('main')->skipTrial()->swap($request->input('plan.data.id'));

            } else {

                // Create subscription
                $user->newSubscription('main', $request->input('plan.data.id'))->create($paymentMethod);
            }

        } catch (IncompletePayment $exception) {

            throw new HttpException(400, 'We can\'t charge your card');
        }
    }

    /**
     * Create plan
     *
     * @param $request
     * @return mixed
     */
    public function createPlan($request)
    {
        $product = $this->stripe->products()->create([
            'name'                 => $request->input('attributes.name'),
            'description'          => $request->input('attributes.description'),
            'metadata'             => [
                'capacity' => $request->input('attributes.capacity')
            ]
        ]);

        $plan = $this->stripe->plans()->create([
            'id'       => Str::slug($request->input('attributes.name')),
            'amount'   => $request->input('attributes.price'),
            'currency' => 'USD',
            'interval' => 'month',
            'product'  => $product['id'],
        ]);

        return compact('plan', 'product');
    }

    /**
     * Update plan
     *
     * @param $request
     * @param $id
     */
    public function updatePlan($request, $id)
    {
        $plan_colls = ['is_active', 'price'];
        $product_colls = ['name', 'description', 'capacity'];

        $plan = $this->stripe->plans()->find($id);

        // Update product
        if (in_array($request->name, $product_colls)) {

            if ($request->name === 'capacity') {
                $this->stripe->products()->update($plan['product'], ['metadata' => ['capacity' => $request->value]]);
            }
            if ($request->name === 'name') {
                $this->stripe->products()->update($plan['product'], ['name' => $request->value]);
            }
            if ($request->name === 'description') {
                $this->stripe->products()->update($plan['product'], ['description' => $request->value]);
            }
        }

        // Update plan
        if (in_array($request->name, $plan_colls)) {

            if ($request->name === 'is_active') {
                $this->stripe->plans()->update($id, ['active' => $request->value]);
            }
        }
    }

    /**
     * Get plan details
     *
     * @param $id
     * @return mixed
     */
    public function getPlan($id)
    {
        $plan = $this->stripe->plans()->find($id);
        $product = $this->stripe->products()->find($plan['product']);

        return compact('plan', 'product');
    }

    /**
     * Get all plans
     *
     * @return mixed
     */
    public function getPlans()
    {
        // Get stripe plans
        $stripe_plans = $this->stripe->plans()->all();

        // Plans container
        $plans = [];

        foreach ($stripe_plans['data'] as $plan) {

            // Get stripe product
            $product = $this->stripe->products()->find($plan['product']);

            // Push data to $plan container
            array_push($plans, [
                'plan'    => $plan,
                'product' => $product,
            ]);
        }

        return $plans;
    }

    /**
     * Get all active plans
     *
     * @return mixed
     */
    public function getActivePlans()
    {
        // Get stripe plans
        $stripe_plans = $this->stripe->plans()->all();

        // Plans container
        $plans = [];

        foreach ($stripe_plans['data'] as $plan) {

            if ($plan['active']) {

                // Get stripe product
                $product = $this->stripe->products()->find($plan['product']);

                // Push data to $plan container
                array_push($plans, [
                    'plan'    => $plan,
                    'product' => $product,
                ]);
            }
        }

        return $plans;
    }

    /**
     * Delete plan
     *
     * @param $slug
     */
    public function deletePlan($slug)
    {
        $this->stripe->plans()->delete($slug);
    }
}