<?php
namespace VueFileManager\Subscription\Support\Engines;

use ErrorException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\Models\PlanDriver;
use VueFileManager\Subscription\Support\Webhooks\PayPalWebhooks;
use VueFileManager\Subscription\Support\Services\PayPalHttpClient;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;

class PayPalEngine implements Engine
{
    use PayPalWebhooks;
    use PayPalHttpClient;

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#plans_create
     *
     * @throws ErrorException
     */
    public function createFixedPlan(CreateFixedPlanData $data): array
    {
        $productId = $this->getOrCreateProductId();

        $plan = $this->post('/billing/plans', [
            'product_id'          => $productId,
            'name'                => $data->name,
            'description'         => $data->description,
            'billing_cycles'      => [
                [
                    'frequency'      => [
                        'interval_unit'  => mapPayPalInterval($data->interval),
                        'interval_count' => 1,
                    ],
                    'tenure_type'    => 'REGULAR',
                    'sequence'       => 1,
                    'total_cycles'   => 0,
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value'         => $data->amount,
                            'currency_code' => $data->currency,
                        ],
                    ],
                ],
            ],
            'payment_preferences' => [
                'auto_bill_outstanding'     => true,
                'payment_failure_threshold' => 3,
            ],
        ]);

        // Check if there is any error
        if ($plan->failed()) {
            throw new ErrorException($plan->json()['message']);
        }

        return [
            'id'   => $plan->json()['id'],
            'name' => $plan->json()['name'],
        ];
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#plans_patch
     */
    public function updatePlan(Plan $plan): Response
    {
        $response = $this->patch("/billing/plans/{$plan->driverId('paypal')}", [
            [
                'op'    => 'replace',
                'path'  => '/name',
                'value' => $plan->name,
            ],
            [
                'op'    => 'replace',
                'path'  => '/description',
                'value' => $plan->description,
            ],
        ]);

        return $response;
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#plans_get
     */
    public function getPlan(string $planId): array
    {
        return $this->get("/billing/plans/$planId")->json();
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#plans_deactivate
     */
    public function deletePlan(string $planId): void
    {
        $this->post("/billing/plans/{$planId}/deactivate", []);
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions_get
     */
    public function getSubscription(string $subscriptionId): Response
    {
        return $this->get("/billing/subscriptions/$subscriptionId");
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions_revise
     */
    public function swapSubscription(Subscription $subscription, Plan $plan): Response
    {
        return $this->post("/billing/subscriptions/{$subscription->driverId()}/revise", [
            'plan_id'             => $plan->driverId('paypal'),
            'application_context' => [
                'url' => url('/user/settings/billing'),
            ],
        ]);
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions_revise
     */
    public function updateSubscription(Subscription $subscription, ?Plan $plan = null): array
    {
        $response = $this->post("/billing/subscriptions/{$subscription->driverId()}/revise", [
            'plan_id'             => $plan->driverId('paypal'),
            'application_context' => [
                'return_url' => url('/user/settings/billing'),
            ],
        ]);

        return [
            'driver' => 'paypal',
            'url'    => $response->json()['links'][0]['href'],
        ];
    }

    /**
     * https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions_cancel
     */
    public function cancelSubscription(Subscription $subscription): Response
    {
        // Get subscription details from payment gateway
        $originalSubscription = $this->getSubscription($subscription->driverId());

        // Cancel subscription request
        $response = $this->post("/billing/subscriptions/{$subscription->driverId()}/cancel", [
            'reason' => 'User decided cancel his subscription',
        ]);

        // Store end_at period and update status as cancelled
        $subscription->update([
            'status'  => 'cancelled',
            'ends_at' => $originalSubscription->json()['billing_info']['next_billing_time'],
        ]);

        return $response;
    }

    /**
     * https://developer.paypal.com/docs/api/webhooks/v1/
     */
    public function webhook(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        // Verify PayPal webhook
        // TODO: temporarily disabled, it's been issue there when if PaymentSaleCompleted webhook is sent, verification will be failed, who know why
        /*$response = $this->post('/notifications/verify-webhook-signature', [
            'auth_algo'         => $request->header('PAYPAL-AUTH-ALGO'),
            'cert_url'          => $request->header('PAYPAL-CERT-URL'),
            'transmission_id'   => $request->header('PAYPAL-TRANSMISSION-ID'),
            'transmission_sig'  => $request->header('PAYPAL-TRANSMISSION-SIG'),
            'transmission_time' => $request->header('PAYPAL-TRANSMISSION-TIME'),
            'webhook_id'        => config('subscription.credentials.paypal.webhook_id'),
            'webhook_event'     => $request->all(),
        ]);

        // Check response
        if ($response->json()['verification_status'] !== 'SUCCESS') {
            throw new SuspiciousOperationException('This request is counterfeit.', 401);
        }*/

        // Extract method name
        $method = 'handle' . Str::studly(str_replace('.', '_', strtolower($request->input('event_type'))));

        if (method_exists($this, $method)) {
            $this->{$method}($request);
        } else {
            Log::error("Method: $method didn't exists!");
        }

        return new \Symfony\Component\HttpFoundation\Response('Webhook Handled', 200);
    }

    /**
     * If isn't any product created, create them. If there is
     * some product, then get his id.
     *
     * @throws ErrorException
     */
    private function getOrCreateProductId(): string
    {
        $paypalPlan = PlanDriver::where('driver', 'paypal')
            ->first();

        if ($paypalPlan) {
            $plan = $this->getPlan($paypalPlan->driver_plan_id);

            // Check if product id was retrieved
            if (array_key_exists('product_id', $plan)) {
                return $plan['product_id'];
            }
        }

        $response = $this->post('/catalogs/products', [
            'name'        => 'Subscription Service',
            'description' => 'Cloud subscription service',
            'type'        => 'SERVICE',
            'category'    => 'SOFTWARE',
        ]);

        // Check if there is any error
        if ($response->failed()) {
            throw new ErrorException($response->json()['message']);
        }

        return $response->json()['id'];
    }

    public function createCustomer(array $user): null|Response
    {
        return null;
    }

    public function updateCustomer(array $user): null|Response
    {
        return null;
    }

    public function createSubscription(Plan $plan, $user = null): array
    {
        return [];
    }
}
