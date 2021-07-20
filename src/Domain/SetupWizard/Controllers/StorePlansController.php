<?php
namespace Domain\SetupWizard\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Subscriptions\Services\StripeService;
use Domain\SetupWizard\Requests\StoreStripePlansRequest;

/**
 * Create Stripe subscription plan
 */
class StorePlansController extends Controller
{
    public function __construct(
        public StripeService $stripe,
    ) {
    }

    public function store_stripe_plans(
        StoreStripePlansRequest $request
    ): Response {
        foreach ($request->input('plans') as $plan) {
            $this->stripe->createPlan($plan);
        }

        return response('Done', 204);
    }
}
