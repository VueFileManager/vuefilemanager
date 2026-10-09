<?php
namespace VueFileManager\Subscription\Domain\Plans\Actions;

use Spatie\QueueableAction\QueueableAction;
use VueFileManager\Subscription\Support\EngineManager;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;

class DeletePlansFromPaymentServiceAction
{
    use QueueableAction;

    public function __construct(
        public EngineManager $subscription
    ) {
    }

    public function __invoke(Plan $plan): void
    {
        // Delete plan from all available payment gateways
        collect($plan->drivers->pluck('driver'))
            ->each(fn ($driver) => $this->subscription
            ->driver($driver)
            ->deletePlan(
                $plan->driverId($driver)
            ));
    }
}
