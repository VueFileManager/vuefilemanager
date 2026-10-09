<?php
namespace VueFileManager\Subscription\Support\Engines;

use Carbon\Carbon;
use ErrorException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Stripe\WebhookSignature;
use Illuminate\Http\Client\Response;
use Stripe\Exception\SignatureVerificationException;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Support\Webhooks\StripeWebhooks;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Support\Services\StripeHttpClient;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class StripeEngine implements Engine
{
    use StripeWebhooks;
    use StripeHttpClient;

    /*
     * https://stripe.com/docs/api/prices/retrieve?lang=php
     */
    public function getPlan(string $planId): array
    {
        return $this->get("/prices/$planId")->json();
    }

    /**
     * https://stripe.com/docs/api/products/create?lang=php
     * https://stripe.com/docs/api/prices/create?lang=php
     *
     * @throws ErrorException
     */
    public function createFixedPlan(CreateFixedPlanData $data): array
    {
        // Create product
        $product = $this->post('/products', [
            'url'         => url('/'),
            'name'        => $data->name,
            'description' => $data->description,
        ]);

        // Check if there is any error
        if ($product->failed()) {
            throw new ErrorException($product->json()['error']['message']);
        }

        // Next create subscription plan
        $price = $this->post('/prices', [
            'product'     => $product->json()['id'],
            'currency'    => strtolower($data->currency),
            'unit_amount' => $data->amount * 100,
            'recurring'   => [
                'interval' => $data->interval,
            ],
        ]);

        // Check if there is any error
        if ($price->failed()) {
            throw new ErrorException($price->json()['error']['message']);
        }

        return [
            'id'   => $price->json()['id'],
            'name' => $data->name,
        ];
    }

    /*
     * https://stripe.com/docs/api/products/update?lang=php
     */
    public function updatePlan(Plan $plan): Response
    {
        // Update stripe product where are stored name and description
        return $this->post("/products/{$plan->driverId('stripe')}", [
            'name'        => $plan->name,
            'description' => $plan->description,
        ]);
    }

    /*
     * https://stripe.com/docs/api/plans/delete?lang=php
     */
    public function deletePlan(string $planId): void
    {
        $price = $this->getPlan($planId);

        // Delete product price
        $this->delete("/plans/$planId");

        // Delete product
        $this->delete("/products/{$price['product']}");
    }

    /**
     * https://stripe.com/docs/api/customers/create
     *
     * @throws ErrorException
     */
    public function createCustomer(array $user): Response
    {
        $response = $this->post('/customers', [
            'metadata' => [
                'id' => $user['id'],
            ],
            'email'    => $user['email'],
            'name'     => $user['name'] . ' ' . $user['surname'],
            'phone'    => $user['phone'] ?? null,
        ]);

        // Check if there is any error
        if ($response->failed()) {
            throw new ErrorException($response->json()['error']['message']);
        }

        // Store customer id to the database
        Customer::create([
            'user_id'        => $user['id'],
            'driver_user_id' => $response->json()['id'],
            'driver'         => 'stripe',
        ]);

        return $response;
    }

    /*
     * https://stripe.com/docs/api/customers/update
     */
    public function updateCustomer(array $user): Response
    {
        // Get stripe customer id
        $user = config('auth.providers.users.model')::find($user['id']);

        // Update customer request
        return $this->post("/customers/{$user->customerId('stripe')}", [
            'email' => $user['email'],
            'name'  => $user['name'] . ' ' . $user['surname'],
            'phone' => $user['phone'] ?? null,
        ]);
    }

    /*
     * https://stripe.com/docs/api/subscriptions/retrieve?lang=curl
     */
    public function getSubscription(string $subscriptionId): Response
    {
        return $this->get("/subscriptions/$subscriptionId");
    }

    /*
     * https://stripe.com/docs/api/subscriptions/create
     */
    public function createSubscription(Plan $plan, $user = null): array
    {
        $price = $this->getPlan($plan->driverId('stripe'));

        $response = $this->post('/subscriptions', [
            'customer' => $user->customerId('stripe'),
            'items'    => [
                [
                    'price' => $price['id'],
                ],
            ],
        ]);

        return $response->json();
    }

    /*
     * https://stripe.com/docs/api/subscriptions/update?lang=php
     */
    public function swapSubscription(Subscription $subscription, Plan $plan): Response
    {
        // Get subscription to obtain subscription item code
        $stripeSubscription = $this->getSubscription($subscription->driverId());

        // Get product to obtain price id
        $originalPlan = $this->getPlan($plan->driverId('stripe'));

        return $this->post("/subscriptions/{$subscription->driverId()}", [
            'items' => [
                [
                    'id'    => $stripeSubscription->json()['items']['data'][0]['id'],
                    'price' => $originalPlan['id'],
                ],
            ],
        ]);
    }

    /*
     * https://stripe.com/docs/api/subscriptions/retrieve?lang=curl
     */
    public function cancelSubscription(Subscription $subscription): Response
    {
        // Send cancel subscription request
        $response = $this->delete("/subscriptions/{$subscription->driverId()}");

        // Store end_at period and update status as cancelled
        $subscription->update([
            'status'  => 'cancelled',
            'ends_at' => Carbon::createFromTimestamp($response->json()['current_period_end']),
        ]);

        return $response;
    }

    /*
     * https://stripe.com/docs/webhooks
     */
    public function webhook(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        // Verify signature
        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                config('subscription.credentials.stripe.webhook_key'),
                300
            );
        } catch (SignatureVerificationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage(), $exception);
        }

        // Extract method name
        $method = 'handle' . Str::studly(str_replace('.', '_', $request->input('type')));

        if (method_exists($this, $method)) {
            $this->{$method}($request);
        }

        return new \Symfony\Component\HttpFoundation\Response('Webhook Handled', 200);
    }

    public function updateSubscription(Subscription $subscription, ?Plan $plan = null): array
    {
        return [];
    }
}
