<?php
namespace VueFileManager\Subscription\Domain\Plans\Actions;

use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateMeteredPlanData;

class StoreMeteredPlanAction
{
    public function __invoke(CreateMeteredPlanData $meteredPlanData)
    {
        // Store plan
        $plan = Plan::create([
            'type'        => 'metered',
            'name'        => $meteredPlanData->name,
            'description' => $meteredPlanData->description,
            'currency'    => $meteredPlanData->currency,
        ]);

        foreach ($meteredPlanData->meters as $meter) {
            // Store metered item
            $price = $plan->meteredFeatures()->create([
                'key'                => $meter['key'],
                'aggregate_strategy' => $meter['aggregate_strategy'],
            ]);

            collect($meter['tiers'])->each(
                fn ($tier) =>
                $price->tiers()->create([
                    'first_unit' => $tier['first_unit'],
                    'last_unit'  => $tier['last_unit'],
                    'per_unit'   => $tier['per_unit'],
                    'flat_fee'   => $tier['flat_fee'],
                ])
            );
        }

        return $plan;
    }
}
