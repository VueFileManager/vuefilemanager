<?php
namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\DemoService;
use Illuminate\Http\Response;
use App\Services\StripeService;
use Laravel\Cashier\Subscription;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\UsersCollection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class PlanController extends Controller
{
    /**
     * @param StripeService $stripe
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
        $this->demo = resolve(DemoService::class);
    }

    /**
     * Get all plans
     *
     * @return PlanCollection|Application|ResponseFactory|Response
     */
    public function index()
    {
        // Store or Get plans to cache
        if (Cache::has('plans')) {
            $plans = Cache::get('plans');
        } else {
            $plans = Cache::rememberForever('plans', function () {
                return $this->stripe->getPlans();
            });
        }

        return response(new PlanCollection($plans), 200);
    }

    /**
     * Get plan record
     *
     * @param $id
     * @return PlanResource|Application|ResponseFactory|Response
     */
    public function show($id)
    {
        // Store or Get plan to cache
        if (Cache::has('plan-' . $id)) {
            $plan = Cache::get('plan-' . $id);
        } else {
            $plan = Cache::rememberForever('plan-' . $id, function () use ($id) {
                return $this->stripe->getPlan($id);
            });
        }

        return response(new PlanResource($plan), 200);
    }

    /**
     * Create new plan
     *
     * @param Request $request
     * @return PlanResource|Application|ResponseFactory|Response
     */
    public function store(Request $request)
    {
        // TODO: inline request
        if (is_demo()) {
            if (Cache::has('plan-starter-pack')) {
                $plan = Cache::get('plan-starter-pack');
            } else {
                $plan = Cache::rememberForever('plan-starter-pack', function () {
                    return $this->stripe->getPlan('starter-pack');
                });
            }

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
     *
     * @param Request $request
     * @param $id
     * @return ResponseFactory|Response
     */
    public function update(Request $request, $id)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Update plan
        $this->stripe->updatePlan($request, $id);

        // Clear cached plans
        cache_forget_many(['plans', 'pricing', 'plan-' . $id]);

        return response('Saved!', 201);
    }

    /**
     * Delete plan
     *
     * @param $id
     * @return ResponseFactory|Response
     */
    public function delete($id)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Delete plan
        $this->stripe->deletePlan($id);

        // Clear cached plans
        cache_forget_many(['plans', 'pricing']);

        return response('Done!', 204);
    }

    /**
     * Get subscriptions
     *
     * @param $id
     * @return mixed
     */
    public function subscribers($id)
    {
        $subscribers = Subscription::whereStripePlan($id)
            ->pluck('user_id');

        return new UsersCollection(
            User::sortable()
                ->findMany($subscribers)
        );
    }
}
