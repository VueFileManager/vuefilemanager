<?php
namespace VueFileManager\Subscription\Support\Engines;

use Exception;
use ErrorException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Support\Webhooks\PayStackWebhooks;
use VueFileManager\Subscription\Support\Services\PayStackHttpClient;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class PayStackEngine implements Engine
{
    use PayStackWebhooks;
    use PayStackHttpClient;

    /**
     * https://paystack.com/docs/api/#plan-create
     *
     * @throws ErrorException
     */
    public function createFixedPlan(CreateFixedPlanData $data): array
    {
        // Get currency
        $currency = config('subscription.is_local')
            ? 'ZAR'
            : $data->currency;

        $response = $this->post('/plan', [
            'name'     => $data->name,
            'currency' => $currency,
            'amount'   => $data->amount * 100,
            'interval' => mapPaystackIntervals($data->interval),
        ]);

        // Check if there is any error
        if ($response->json()['status'] === false) {
            throw new ErrorException($response->json()['message']);
        }

        return [
            'id'   => $response->json()['data']['plan_code'],
            'name' => $response->json()['data']['name'],
        ];
    }

    /**
     * https://paystack.com/docs/api/#plan-create
     */
    public function updatePlan(Plan $plan): Response
    {
        $response = $this->put("/plan/{$plan->driverId('paystack')}", [
            'name' => $plan->name,
        ]);

        return $response;
    }

    /**
     * https://paystack.com/docs/api/#plan-fetch
     */
    public function getPlan(string $planId): array
    {
        $response = $this->get("/plan/$planId");

        // Check if subscription exist
        if (! $response->json()['status']) {
            throw new NotFoundHttpException($response->json()['message']);
        }

        return $response->json();
    }

    /**
     * Not documented, but it's working
     */
    public function deletePlan(string $planId): void
    {
        $this->delete("/plan/$planId");
    }

    /**
     * https://paystack.com/docs/api/#customer-create
     */
    public function createCustomer(array $user): Response
    {
        $response = $this->post('/customer', [
            'email'      => $user['email'],
            'first_name' => $user['name'],
            'last_name'  => $user['surname'],
            'phone'      => $user['phone'],
        ]);

        // Store customer id to the database
        Customer::create([
            'user_id'        => $user['id'],
            'driver_user_id' => $response->json()['data']['customer_code'],
            'driver'         => 'paystack',
        ]);

        return $response;
    }

    /**
     * https://paystack.com/docs/api/#customer-update
     */
    public function updateCustomer(array $user): Response
    {
        // Get paystack customer id
        $user = config('auth.providers.users.model')::find($user['id']);

        return $this->put("/customer/{$user->customerId('paystack')}", [
            'email'      => $user['email'],
            'first_name' => $user['name'],
            'last_name'  => $user['surname'],
            'phone'      => $user['phone'],
        ]);
    }

    /**
     * https://paystack.com/docs/api/#subscription-fetch
     */
    public function getSubscription(string $subscriptionId): Response
    {
        $response = $this->get("/subscription/$subscriptionId");

        // Check if subscription exist
        if (! $response->json()['status']) {
            throw new NotFoundHttpException($response->json()['message']);
        }

        return $response;
    }

    /*
     * https://paystack.com/docs/api/#subscription-manage-link
     */
    public function updateSubscription(Subscription $subscription, ?Plan $plan = null): array
    {
        $response = $this->get("/subscription/{$subscription->driverId()}/manage/link");

        return [
            'driver' => 'paystack',
            'url'    => $response->json()['data']['link'],
        ];
    }

    /**
     * https://paystack.com/docs/api/#subscription-disable
     */
    public function cancelSubscription(Subscription $subscription): Response
    {
        // Get subscription details from payment gateway
        $subscriptionDetail = $this->getSubscription($subscription->driverId());

        // Send cancel subscription request
        $response = $this->post('/subscription/disable', [
            'code'  => $subscriptionDetail->json()['data']['subscription_code'],
            'token' => $subscriptionDetail->json()['data']['email_token'],
        ]);

        if (! $response->json()['status']) {
            //TODO: create exception
        }

        // Store end_at period and update status as cancelled
        $subscription->update([
            'status'  => 'cancelled',
            'ends_at' => $subscriptionDetail->json()['data']['next_payment_date'],
        ]);

        return $response;
    }

    /**
     * https://paystack.com/docs/payments/webhooks
     * @throws Exception
     */
    public function webhook(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        // Hash request
        $hash = hash_hmac('sha512', $request->getContent(), config('subscription.credentials.paystack.secret'));

        // validate event do all at once to avoid timing attack
        if(! $request->hasHeader('x-paystack-signature') && $request->header('x-paystack-signature') !== $hash) {
            Log::info('The webhook cannot be verified.');

            throw new SuspiciousOperationException('This request is counterfeit.', 401);
        }

        // Extract method name
        $method = 'handle' . Str::studly(str_replace('.', '_', $request->input('event')));

        if (method_exists($this, $method)) {
            $this->{$method}($request);
        }

        return new \Symfony\Component\HttpFoundation\Response('Webhook Handled', 200);
    }

    public function swapSubscription(Subscription $subscription, Plan $plan): Response
    {
    }

    public function createSubscription(Plan $plan, $user = null): array
    {
        return [];
    }
}
