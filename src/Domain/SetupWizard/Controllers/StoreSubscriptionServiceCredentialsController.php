<?php
namespace Domain\SetupWizard\Controllers;

use Stripe;
use Artisan;
use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Domain\SetupWizard\Requests\StoreStripeCredentialsRequest;

class StoreSubscriptionServiceCredentialsController extends Controller
{
    /**
     * Store and test stripe credentials
     */
    public function store_stripe_credentials(
        StoreStripeCredentialsRequest $request
    ): Response {
        if (! app()->runningUnitTests()) {
            // Create stripe instance
            $stripe = Stripe::make($request->input('secret'), '2020-03-02');

            try {
                // Try to get stripe account details
                $stripe->account()->details();
            } catch (UnauthorizedException $e) {
                throw new HttpException(401, $e->getMessage());
            }
        }

        // Set settings
        collect([
            [
                'name'  => 'stripe_currency',
                'value' => $request->input('currency'),
            ],
            [
                'name'  => 'payments_configured',
                'value' => 1,
            ],
            [
                'name'  => 'payments_active',
                'value' => 1,
            ],
        ])->each(function ($col) {
            Setting::forceCreate([
                'name'  => $col['name'],
                'value' => $col['value'],
            ]);
        });

        if (! app()->runningUnitTests()) {
            // Set stripe credentials to .env
            setEnvironmentValue([
                'CASHIER_CURRENCY'      => $request->input('currency'),
                'STRIPE_KEY'            => $request->input('key'),
                'STRIPE_SECRET'         => $request->input('secret'),
                'STRIPE_WEBHOOK_SECRET' => $request->input('webhookSecret'),
            ]);

            // Clear cache
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
