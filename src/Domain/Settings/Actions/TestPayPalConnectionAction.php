<?php
namespace Domain\Settings\Actions;

use ErrorException;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;

class TestPayPalConnectionAction
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
                'subscription.credentials.paypal' => [
                    'secret'     => $credentials['secret'],
                    'id'         => $credentials['key'],
                    'webhook_id' => $credentials['webhook'],
                    'is_live'    => $credentials['live'],
                ],
            ]);

            // Define test plan
            $data = CreateFixedPlanData::fromArray([
                'type'        => 'fixed',
                'name'        => 'Test Plan',
                'description' => 'Test plan description...',
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
                ->driver('paypal')
                ->createFixedPlan($data);

            // Delete plan
            $this->subscription
                ->driver('paypal')
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
