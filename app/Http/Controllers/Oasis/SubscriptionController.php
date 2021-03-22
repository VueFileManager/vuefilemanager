<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\SubscriptionRequestResource;
use App\Models\Oasis\SubscriptionRequest;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Get subscription request details
     *
     * @param SubscriptionRequest $order
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get_subscription_request(SubscriptionRequest $order)
    {
        return response(
            new SubscriptionRequestResource($order), 200
        );
    }
}
