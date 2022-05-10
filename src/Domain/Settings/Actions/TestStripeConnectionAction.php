<?php
namespace Domain\Settings\Actions;

use ErrorException;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;

class TestStripeConnectionAction
{
    public function __construct(
        public EngineManager $subscription
    ) {
    }

    public function __invoke($credentials)
    {
        try {
            // Set temporary stripe connection
            config([
                'subscription.credentials.stripe' => [
                    'secret'      => $credentials['secret'],
                    'public_key'  => $credentials['key'],
                    'webhook_key' => $credentials['webhook'],
                ],
            ]);

            // Define test plan
            $data = CreateFixedPlanData::fromArray([
                'type'        => 'fixed',
                'name'        => 'Test Plan',
                'description' => null,
                'features'    => [
                    'max_storage_amount' => 200,
                    'max_team_members'   => 20,
                ],
                'currency'    => 'EUR',
                'amount'      => 99,
                'interval'    => 'month',
            ]);

            // Create test plan
            $plan = $this->subscription
                ->driver('stripe')
                ->createFixedPlan($data);

            // Delete plan
            $this->subscription
                ->driver('stripe')
                ->deletePlan($plan['id']);
        } catch (ErrorException $error) {
            abort(
                response()->json([
                    'type'    => 'service-connection-error',
                    'title'   => 'Service Connection Error',
                    'message' => $error->getMessage(),
                ], 401)
            );
        }
    }
}
