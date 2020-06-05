<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\PlanResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersCollection;
use App\Plan;
use App\User;
use Illuminate\Http\Request;
use Rinvex\Subscriptions\Models\PlanFeature;

class PlanController extends Controller
{
    /**
     * Get all plans
     *
     * @return PlanCollection
     */
    public function index()
    {
        return new PlanCollection(
            app('rinvex.subscriptions.plan')->all()
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
            app('rinvex.subscriptions.plan')->find($id)
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
        $plan = app('rinvex.subscriptions.plan')->create([
            'description'    => $request->input('attributes.description'),
            'price'          => $request->input('attributes.price'),
            'name'           => $request->input('attributes.name'),
            'currency'       => 'USD',
            'invoice_period' => 1,
            'sort_order'     => 1,
            'signup_fee'     => 0,
        ]);

        // Create multiple plan features at once
        $plan->features()->saveMany([
            new PlanFeature([
                'name' => 'Storage capacity',
                'value' => $request->input('attributes.capacity'),
                'sort_order' => 1
            ]),
        ]);

        return new PlanResource($plan);
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
        // TODO: validation request
        $plan = app('rinvex.subscriptions.plan')->find($id);

        if ($request->name === 'capacity') {
            $plan->getFeatureBySlug('storage-capacity')->update(['value' => $request->value]);
        } else {
            $plan->update(make_single_input($request));
        }

        return response('Saved!', 204);
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
