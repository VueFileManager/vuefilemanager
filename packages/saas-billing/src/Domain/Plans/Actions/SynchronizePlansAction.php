<?php
namespace VueFileManager\Subscription\Domain\Plans\Actions;

use ErrorException;
use Illuminate\Support\Facades\Log;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;

class SynchronizePlansAction
{
    public function __construct(
        private EngineManager $subscription,
    ) {
    }

    public function __invoke(): array
    {
        // Message bag
        $errorMessages = [];

        // Check if there are some uncreated plans
        $plans = Plan::where('status', 'active')
            ->where('type', 'fixed')
            ->get();

        // Iterate through the plans
        foreach ($plans as $plan) {
            // Get active drivers
            $driversToSynchronize = array_diff(
                getActiveDrivers(),
                $plan->drivers()->pluck('driver')->toArray()
            );

            // Create missing plans
            foreach ($driversToSynchronize as $driver) {
                try {
                    // Format message
                    $message = "Creating plan {$plan->name}... for $driver";

                    // Error log
                    Log::info($message);

                    if (! app()->runningUnitTests()) {
                        Log::channel('stderr')->info($message);
                    }

                    // Format data
                    $data = CreateFixedPlanData::fromArray([
                        'type'        => 'fixed',
                        'name'        => $plan->name,
                        'description' => $plan->description,
                        'interval'    => $plan->interval,
                        'amount'      => $plan->amount,
                        'currency'    => $plan->currency,
                        'features'    => [],
                    ]);

                    $newPlan = $this->subscription
                        ->driver($driver)
                        ->createFixedPlan($data);

                    // Attach driver plan id into internal plan record
                    $plan
                        ->drivers()
                        ->create([
                            'driver_plan_id' => $newPlan['id'],
                            'driver'         => $driver,
                        ]);
                } catch (ErrorException $error) {
                    // Format message
                    $message = "Creating plan {$plan->name} for $driver failed because of {$error->getMessage()}";

                    // Push the message
                    array_push($errorMessages, $message);

                    // Error log
                    Log::error($message);
                    Log::channel('stderr')->error($message);
                }
            }
        }

        // Update plan via gateways api
        collect(getActiveDrivers())
            ->each(function ($driver) {
                Plan::where('status', 'active')
                    ->where('type', 'fixed')
                    ->cursor()
                    ->each(function ($plan) use ($driver) {
                        if (! app()->runningUnitTests()) {
                            Log::channel('stderr')->info("Synchronizing plans {$plan->name}...");
                        }

                        $this->subscription->driver($driver)->updatePlan($plan);
                    });
            });

        return $errorMessages;
    }
}
