<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\PricingCollection;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * PlanController constructor.
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get all active plans
     *
     * @return PricingCollection
     */
    public function index()
    {
        $collection = new PricingCollection(
            $this->stripe->getActivePlans()
        );

        return $collection->sortBy('product.metadata.capacity')
            ->values()
            ->all();
    }
}
