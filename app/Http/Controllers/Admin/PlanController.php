<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\PlanResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersCollection;
use App\Plan;
use App\Services\StripeService;
use App\User;
use Illuminate\Http\Request;
use Rinvex\Subscriptions\Models\PlanFeature;

class PlanController extends Controller
{
    /**
     * PlanController constructor.
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get all plans
     *
     * @return PlanCollection
     */
    public function index()
    {
        return new PlanCollection(
            $this->stripe->getPlans()
        );
    }

    /**
     * Get plan record
     *
     * @param $id
     * @return PlanResource
     */
    public function show($id)
    {
        return new PlanResource(
            $this->stripe->getPlan($id)
        );
    }

    /**
     * Create new plan
     *
     * @param Request $request
     * @return PlanResource
     */
    public function store(Request $request)
    {
        return new PlanResource(
            $this->stripe->createPlan($request)
        );
    }

    /**
     * Update plan attribute
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->stripe->updatePlan($request, $id);

        return response('Saved!', 204);
    }

    /**
     * Delete plan
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->stripe->deletePlan($id);

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
        $subscribers = app('rinvex.subscriptions.plan')
            ->find($id)
            ->subscriptions
            ->pluck('user_id');

        return new UsersCollection(
            User::findMany($subscribers)
        );
    }
}
