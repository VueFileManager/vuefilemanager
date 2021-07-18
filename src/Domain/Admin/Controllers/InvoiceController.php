<?php
namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Services\StripeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\InvoiceAdminCollection;

class InvoiceController extends Controller
{
    private StripeService $stripe;

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
     * Get single invoice by invoice $token
     *
     * @param $customer
     * @param $token
     * @return InvoiceResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($customer, $token)
    {
        return view('vuefilemanager.invoice')
            ->with('settings', get_settings_in_json())
            ->with('invoice', $this->stripe->getUserInvoice($customer, $token));
    }
}
