<?php
namespace Domain\Admin\Controllers;

use Domain\Settings\Models\Invoice;
use App\Http\Controllers\Controller;
use Domain\Admin\Resources\InvoiceResource;
use Domain\SetupWizard\Services\StripeService;
use Domain\Admin\Resources\InvoiceAdminCollection;

class InvoiceController extends Controller
{
    public function __construct(
        private StripeService $stripe
    ) {
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
