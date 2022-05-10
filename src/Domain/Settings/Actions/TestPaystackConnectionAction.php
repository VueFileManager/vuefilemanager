<?php
namespace Domain\Settings\Actions;

use ErrorException;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;

class TestPaystackConnectionAction
{
    public function __construct(
        public EngineManager $subscription
    ) {
    }

    public function __invoke($credentials)
    {
        try {
            // Set temporary paystack connection
            config([
                'subscription.credentials.paystack' => [
                    'secret'     => $credentials['secret'],
                    'public_key' => $credentials['key'],
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
                'currency'    => 'ZAR',
                'amount'      => 99999,
                'interval'    => 'month',
            ]);

            // Create test plan
            $plan = $this->subscription
                ->driver('paystack')
                ->createFixedPlan($data);

            // Delete plan
            $this->subscription
                ->driver('paystack')
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
