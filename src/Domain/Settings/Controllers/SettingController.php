<?php
namespace App\Http\Controllers\Admin;

use Stripe;
use Artisan;
use Domain\Settings\Models\Setting;
use Illuminate\Http\Request;
use Domain\SetupWizard\Services\DemoService;
use App\Http\Controllers\Controller;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SettingController extends Controller
{
    private $demo;

    public function __construct()
    {
        $this->demo = resolve(DemoService::class);
    }

    /**
     * Get table content
     *
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        if (strpos($request->column, '|') !== false) {
            $columns = explode('|', $request->column);

            return Setting::whereIn('name', $columns)
                ->pluck('value', 'name');
        }

        return Setting::where('name', $request->column)
            ->pluck('value', 'name');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Store image if exist
        if ($request->hasFile($request->name)) {
            // Find and update image path
            Setting::updateOrCreate([
                'name' => $request->name,
            ], [
                'value' => store_system_image($request, $request->name),
            ]);

            return response('Done', 204);
        }

        // Find and update variable
        Setting::updateOrCreate(
            ['name' => $request->name],
            ['value' => $request->value]
        );

        return response('Done', 204);
    }

    /**
     * Set new email credentials to .env file
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function set_email(Request $request)
    {
        // TODO: pridat validator do requestu
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
            setEnvironmentValue([
                'MAIL_DRIVER'     => $request->driver,
                'MAIL_HOST'       => $request->host,
                'MAIL_PORT'       => $request->port,
                'MAIL_USERNAME'   => $request->username,
                'MAIL_PASSWORD'   => $request->password,
                'MAIL_ENCRYPTION' => $request->encryption,
            ]);

            // Clear config cache
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }

    /**
     * Configure stripe additionally
     *
     * @param Request $request
     */
    public function set_stripe(Request $request)
    {
        // TODO: pridat validator do requestu
        // Check payment setup status
        if (get_setting('payments_configured')) {
            abort(401, 'Gone');
        }

        // Try to get stripe account details
        try {
            if (! app()->runningUnitTests()) {
                Stripe::make($request->secret, '2020-03-02')
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
                'value' => $request->currency,
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
                'CASHIER_CURRENCY'      => $request->currency,
                'STRIPE_KEY'            => $request->key,
                'STRIPE_SECRET'         => $request->secret,
                'STRIPE_WEBHOOK_SECRET' => $request->webhookSecret,
            ]);

            // Clear cache
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }

    /**
     * Clear application cache
     */
    public function flush_cache()
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
