<?php
namespace Domain\Plans\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Plans\Resources\PlanResource;
use Domain\Plans\Resources\PlanCollection;
use Domain\Subscriptions\Services\StripeService;

class PlansController extends Controller
{
    public function __construct(
        private StripeService $stripe,
    ) {
    }

    /**
     * Get all plans
     */
    public function index(): Response
    {
        // Store or Get plans to cache
        $plans = cache()
            ->rememberForever('plans', function () {
                return $this->stripe->getPlans();
            });

        return response(new PlanCollection($plans), 200);
    }

    /**
     * Get plan record
     */
    public function show(string $id): Response
    {
        // Store or Get plan to cache
        $plan = cache()
            ->rememberForever("plan-$id", function () use ($id) {
                return $this->stripe->getPlan($id);
            });

        return response(new PlanResource($plan), 200);
    }

    /**
     * Create new plan
     * TODO: store request
     */
    public function store(Request $request): Response | PlanResource
    {
        if (is_demo()) {
            $plan = cache()
                ->rememberForever('plan-starter-pack', function () {
                    return $this->stripe->getPlan('starter-pack');
                });

            return new PlanResource($plan);
        }

        $plan = new PlanResource(
            $this->stripe->createPlan($request)
        );

        // Clear cached plans
        cache_forget_many(['plans', 'pricing']);

        return response($plan, 201);
    }

    /**
     * Update plan attribute
     */
    public function update(Request $request, string $id): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Update plan
        $this->stripe->updatePlan($request, $id);

        // Clear cached plans
        cache_forget_many(['plans', 'pricing', "plan-$id"]);

        return response('Saved!', 201);
    }

    /**
     * Delete plan
     */
    public function delete($id): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Delete plan
        $this->stripe->deletePlan($id);

        // Clear cached plans
        cache_forget_many(['plans', 'pricing']);

        return response('Done!', 204);
    }
}
