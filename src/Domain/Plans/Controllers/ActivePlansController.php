<?php
namespace Domain\Plans\Controllers;

use Illuminate\Support\Facades\Cache;
use Domain\Plans\Resources\PricingCollection;
use Domain\Subscriptions\Services\StripeService;

class ActivePlansController
{
    /**
     * Get all active storage plans
     */
    public function __invoke(): PricingCollection
    {
        // Get pricing from cache
        $pricing = Cache::rememberForever('pricing', function () {
            return resolve(StripeService::class)->getActivePlans();
        });

        // Format pricing to collection
        $collection = new PricingCollection($pricing);

        // Sort and return pricing
        return $collection
            ->sortBy('product.metadata.capacity')
            ->values()
            ->all();
    }
}
