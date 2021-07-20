<?php


namespace Domain\Settings\Controllers;


use Artisan;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Stripe;
use Domain\Settings\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SetStripeController
{
    /**
     * Configure stripe additionally
     */
    public function __invoke(Request $request): Response
    {
        // TODO: pridat validator do requestu
        // Check payment setup status
        if (get_setting('payments_configured')) {
            abort(401, 'Gone');
        }

        // Try to get stripe account details
        try {
            if (! app()->runningUnitTests()) {
                Stripe::make($request->input('secret'), '2020-03-02')
                    ->account()
                    ->details();
            }
        } catch (UnauthorizedException $e) {
            throw new HttpException(401, $e->getMessage());
        }

        // Get options
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
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}