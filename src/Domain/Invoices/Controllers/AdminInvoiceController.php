<?php
namespace Domain\Invoices\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Domain\Subscriptions\Services\StripeService;
use Domain\Admin\Resources\InvoiceAdminCollection;

class AdminInvoiceController extends Controller
{
    public function __construct(
        private StripeService $stripe
    ) {
    }

    /**
     * Get all invoices
     */
    public function index(): InvoiceAdminCollection
    {
        return new InvoiceAdminCollection(
            $this->stripe->getInvoices()['data']
        );
    }

    /**
     * Get single invoice by invoice $token
     */
    public function show(string $customer, string $token): View
    {
        return view('vuefilemanager.invoice')
            ->with('settings', get_settings_in_json())
            ->with('invoice', $this->stripe->getUserInvoice($customer, $token));
    }
}
