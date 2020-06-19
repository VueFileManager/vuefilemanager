<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\GatewayCollection;
use App\Http\Resources\GatewayResource;
use App\Http\Resources\InvoiceCollection;
use App\Invoice;
use App\PaymentGateway;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    /**
     * Get all payment gateways
     *
     * @return GatewayCollection
     */
    public function index()
    {
        return new GatewayCollection(PaymentGateway::all());
    }

    /**
     * Get single payment gateway by slug
     *
     * @param $slug
     * @return GatewayResource
     */
    public function show($slug)
    {
        $gateway = PaymentGateway::where('slug', $slug)->firstOrFail();

        return new GatewayResource($gateway);
    }

    /**
     * Get single payment gateway by slug
     *
     * @param $slug
     * @return InvoiceCollection
     */
    public function show_transactions($slug)
    {
        return new InvoiceCollection(
            Invoice::where('provider', $slug)->get()
        );
    }

    /**
     * Update payment gateway options
     *
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        // TODO: validation request

        $gateway = PaymentGateway::where('slug', $slug)->first();

        // Update text data
        $gateway->update(make_single_input($request));

        return response('Saved!', 204);
    }
}
