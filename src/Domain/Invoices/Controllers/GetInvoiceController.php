<?php
namespace Domain\Invoices\Controllers;

use Domain\Settings\Models\Setting;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Users\Actions\FormatUsageEstimatesAction;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;

class GetInvoiceController extends Controller
{
    public function __construct(
        public FormatUsageEstimatesAction $formatUsageEstimates,
    ) {
    }

    public function __invoke(
        Transaction $invoice
    ): View {
        // Get app settings
        $settings = json_decode(
            Setting::all()
                ->pluck('value', 'name')
        );

        // Format metadata
        if ($invoice->metadata) {
            $invoice->metadata = ($this->formatUsageEstimates)($invoice->currency, $invoice->metadata);
        }

        // Return invoice view
        return view('vuefilemanager.invoice')
            ->with('settings', $settings)
            ->with('invoice', $invoice);
    }
}
