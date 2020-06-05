<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\PricingCollection;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Get all active plans
     *
     * @return PricingCollection
     */
    public function index() {

        return new PricingCollection(
            app('rinvex.subscriptions.plan')->where('is_active', 1)->get()
        );
    }
}
