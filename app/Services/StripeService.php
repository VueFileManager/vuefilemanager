<?php


namespace App\Services;

use App\User;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Laravel\Cashier\Exceptions\PaymentActionRequired;
use Stripe;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StripeService
{
    /**
     * Stripe Service constructor.
     */
    public function __construct()
    {
        $this->stripe = Stripe::make(config('cashier.secret'), '2020-03-02');
    }

    /**
     * Get Stripe account details
     *
     * @return mixed
     */
    public function getAccountDetails()
    {
        $account = $this->stripe->account()->details();

        return $account;
    }

    /**
     * Get setup intent
     *
     * @param $user
     * @return mixed
     */
    public function getSetupIntent($user)
    {
        // Create stripe customer if not exist
        $user->createOrGetStripeCustomer();

        // Return setup intent
        return $user->createSetupIntent();
    }

    /**
     * Get tax rate ids
     * @return array
     */
    public function getTaxRates()
    {
        $tax_rates = $this->stripe->taxRates()->all();

        return $tax_rates['data'];
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
        if (!$request->has('payment.meta.pm') && $user->hasDefaultPaymentMethod()) {

            // Get default payment
            return $user->defaultPaymentMethod()->paymentMethod;
        }

        // Clear cached payment methods
        cache_forget_many([
            'payment-methods-user-' . $user->id,
            'default-payment-methods-user-' . $user->id
        ]);

        if ($request->has('payment.meta.pm') && $user->hasDefaultPaymentMethod()) {

            // Set new payment
            return $user->addPaymentMethod($request->input('payment.meta.pm'))->paymentMethod;

        } else if ($request->has('payment.meta.pm') && !$user->hasDefaultPaymentMethod()) {

            // Set new payment
            return $user->updateDefaultPaymentMethod($request->input('payment.meta.pm'))->paymentMethod;

        } else {

            throw new HttpException(400, 'Something went wrong.');
        }
    }

    /**
     * Register new payment method
     *
     * @param $request
     * @param $user
     * @return mixed
     */
    public function registerNewPaymentMethod($request, $user)
    {
        // Clear cached payment methods
        cache_forget_many([
            'payment-methods-user-' . $user->id,
            'default-payment-methods-user-' . $user->id
        ]);

        // Set new payment method
        $user->addPaymentMethod($request->token)->paymentMethod;

        // Set new default payment
        if ($request->default) {
            $user->updateDefaultPaymentMethod($request->token)->paymentMethod;
        }
    }

    /**
     * Create new subscription or replace by new subscription
     *
     * @param $request
     * @param $user
     * @param $paymentMethod
     * @return \Illuminate\Http\RedirectResponse
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

            if ($exception instanceof PaymentActionRequired) {

                $cashier_route = route('cashier.payment', [$exception->payment->id, 'redirect' => url('/settings/subscription')]);

                throw new HttpException(402, $cashier_route);
            } else {
                throw new HttpException(400, $exception->getMessage());
            }

        }
    }

    /**
     * Update customer details
     *
     * @param $user
     */
    public function updateCustomerDetails($user)
    {
        $user->updateStripeCustomer([
            'name'    => $user->settings->billing_name,
            'phone'   => $user->settings->billing_phone_number,
            'address' => [
                'line1'       => $user->settings->billing_address,
                'city'        => $user->settings->billing_city,
                'country'     => $user->settings->billing_country,
                'postal_code' => $user->settings->billing_postal_code,
                'state'       => $user->settings->billing_state,
            ]
        ]);
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
            if ($product['active'] && isset($product['metadata']['capacity'])) {
                array_push($plans, [
                    'plan'    => $plan,
                    'product' => $product,
                ]);
            }
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
                if ($product['active'] && isset($product['metadata']['capacity'])) {
                    array_push($plans, [
                        'plan'    => $plan,
                        'product' => $product,
                    ]);
                }
            }
        }

        return $plans;
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
     * Create plan
     *
     * @param $data
     * @return mixed
     */
    public function createPlan($data)
    {
        if ($data instanceof Request) {
            $plan = [
                'name'        => $data->input('attributes.name'),
                'description' => $data->input('attributes.description'),
                'price'       => $data->input('attributes.price'),
                'capacity'    => $data->input('attributes.capacity'),
            ];
        } else {
            $plan = [
                'name'        => $data['attributes']['name'],
                'description' => $data['attributes']['description'],
                'price'       => $data['attributes']['price'],
                'capacity'    => $data['attributes']['capacity'],
            ];
        }

        $product = $this->stripe->products()->create([
            'name'        => $plan['name'],
            'description' => $plan['description'],
            'metadata'    => [
                'capacity' => $plan['capacity']
            ]
        ]);

        $plan = $this->stripe->plans()->create([
            'id'       => Str::slug($plan['name']),
            'amount'   => $plan['price'],
            'currency' => config('cashier.currency'),
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
     * Delete plan
     *
     * @param $slug
     */
    public function deletePlan($slug)
    {
        $this->stripe->plans()->delete($slug);
    }

    /**
     * Get all user invoices
     *
     * @param $user
     * @return mixed
     */
    public function getUserInvoices($user)
    {
        return $user->invoices();
    }

    /**
     * Get user invoice by id
     *
     * @param $id
     * @return \Laravel\Cashier\Invoice|null
     */
    public function getUserInvoice($customer, $id)
    {
        $user = User::where('stripe_id', $customer)->firstOrFail();

        return $user->findInvoice($id);
    }

    /**
     * Get all invoices
     *
     * @return mixed
     */
    public function getInvoices()
    {
        return $this->stripe->invoices()->all([
            'limit' => 20
        ]);
    }
}