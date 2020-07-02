<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceAdminCollection;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use App\Services\StripeService;
use App\Setting;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * PlanController constructor.
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get all invoices
     *
     * @return InvoiceAdminCollection
     */
    public function index()
    {
        return new InvoiceAdminCollection(
            $this->stripe->getInvoices()['data']
        );
    }

    /**
     * Get single invoice by $token
     *
     * @param $customer
     * @param $token
     * @return InvoiceResource
     */
    public function show($customer, $token)
    {
        $settings = json_decode(Setting::all()->pluck('value', 'name')->toJson());

        $invoice = $this->stripe->getUserInvoice($customer, $token);

        return view('vuefilemanager.invoice')
            ->with('settings', $settings)
            ->with('invoice', $invoice);
    }
}
