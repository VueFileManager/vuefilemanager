<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanCollection;
use App\Http\Resources\PlanResource;
use App\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Get all plans
     *
     * @return PlanCollection
     */
    public function index() {

        return new PlanCollection(Plan::all());
    }

    /**
     * Get plan record
     *
     * @param $id
     * @return PlanResource
     */
    public function show($id)
    {
        $plan = Plan::findOrFail($id);

        return new PlanResource($plan);
    }

    /**
     * Create new plan
     *
     * @param Request $request
     * @return PlanResource
     */
    public function store(Request $request)
    {
        // TODO: validation request

        $plan = Plan::create($request->input('attributes'));

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
        $plan = Plan::findOrFail($id);

        // Update text data
        $plan->update(make_single_input($request));

        return response('Saved!', 204);
    }
}
