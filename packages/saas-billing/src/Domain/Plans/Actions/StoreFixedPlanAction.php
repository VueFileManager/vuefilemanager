<?php
namespace VueFileManager\Subscription\Domain\Plans\Actions;

use ErrorException;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;

class StoreFixedPlanAction
{
    public function __construct(
        private EngineManager $subscription,
    ) {
    }

    public function __invoke(CreateFixedPlanData $data)
    {
        // Create plan
        $plan = Plan::create([
            'type'        => 'fixed',
            'name'        => $data->name,
            'description' => $data->description,
            'interval'    => $data->interval,
            'amount'      => $data->amount,
            'currency'    => $data->currency,
        ]);

        // Create features
        foreach ($data->features as $feature => $value) {
            $plan->fixedFeatures()->create([
                'key'   => $feature,
                'value' => $value,
            ]);
        }

        // Create plan in available gateways
        collect(getActiveDrivers())
            ->each(function ($driver) use ($data, $plan) {
                try {
                    // Create plan via gateway api
                    $driverPlan = $this->subscription
                        ->driver($driver)
                        ->createFixedPlan($data);

                    // Attach driver plan id into internal plan record
                    $plan
                        ->drivers()
                        ->create([
                            'driver_plan_id' => $driverPlan['id'],
                            'driver'         => $driver,
                        ]);
                } catch (ErrorException $error) {
                    // Delete previously created plan
                    $plan->delete();

                    // Return error response
                    abort(
                        response()->json([
                            'type'    => 'plan-creation-error',
                            'title'   => "Plan couldn't be created",
                            'message' => $error->getMessage(),
                        ], 500)
                    );
                }
            });

        return $plan;
    }
}
