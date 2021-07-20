<?php
namespace Domain\SetupWizard\Controllers;

use Artisan;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Domain\SetupWizard\Requests\StoreStripeBillingRequest;

class StoreBillingsController extends Controller
{
    /**
     * Store Stripe billings
     */
    public function __invoke(
        StoreStripeBillingRequest $request
    ): Response {
        collect([
            [
                'name'  => 'billing_phone_number',
                'value' => $request->input('billing_phone_number'),
            ],
            [
                'name'  => 'billing_postal_code',
                'value' => $request->input('billing_postal_code'),
            ],
            [
                'name'  => 'billing_vat_number',
                'value' => $request->input('billing_vat_number'),
            ],
            [
                'name'  => 'billing_address',
                'value' => $request->input('billing_address'),
            ],
            [
                'name'  => 'billing_country',
                'value' => $request->input('billing_country'),
            ],
            [
                'name'  => 'billing_state',
                'value' => $request->input('billing_state'),
            ],
            [
                'name'  => 'billing_city',
                'value' => $request->input('billing_city'),
            ],
            [
                'name'  => 'billing_name',
                'value' => $request->input('billing_name'),
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name'  => $col['name'],
                'value' => $col['value'],
            ]);
        });

        if (! app()->runningUnitTests()) {
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
