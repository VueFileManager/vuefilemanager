<?php

namespace App\Http\Controllers;

use App\Http\Tools\Demo;
use App\Setting;
use Artisan;
use Stripe;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SettingController extends Controller
{
    /**
     * Get table content
     *
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request)
    {
        $column = $request->get('column');

        if (strpos($column, '|') !== false) {

            $columns = explode('|', $column);

            return Setting::whereIn('name', $columns)->pluck('value', 'name');
        }

        return Setting::where('name', $column)->pluck('value', 'name');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        // Store image if exist
        if ($request->hasFile($request->name)) {

            // Store image
            $image_path = store_system_image($request->file($request->name), 'system');

            // Find and update image path
            Setting::updateOrCreate(['name' => $request->name], [
                'value' => $image_path
            ]);

            return response('Done', 204);
        }

        // Find and update variable
        Setting::updateOrCreate(['name' => $request->name], [
            'value' => $request->value
        ]);

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
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        setEnvironmentValue([
            'MAIL_DRIVER'     => $request->input('driver'),
            'MAIL_HOST'       => $request->input('host'),
            'MAIL_PORT'       => $request->input('port'),
            'MAIL_USERNAME'   => $request->input('username'),
            'MAIL_PASSWORD'   => $request->input('password'),
            'MAIL_ENCRYPTION' => $request->input('encryption'),
        ]);

        // Clear config cache
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return response('Done', 204);
    }

    /**
     * Configure stripe additionally
     *
     * @param Request $request
     */
    public function set_stripe(Request $request)
    {
        // Get stripe status
        $is_stripe = get_setting('payments_configured');

        // Check setup status
        if ($is_stripe) abort(401, 'Gone');

        // Create stripe instance
        $stripe = Stripe::make($request->secret, '2020-03-02');

        // Try to get stripe account details
        try {
            $stripe->account()->details();
        } catch (UnauthorizedException $e) {
            throw new HttpException(401, $e->getMessage());
        }

        // Get options
        $settings = collect([
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
        ]);

        // Store options
        $settings->each(function ($col) {
            Setting::updateOrCreate(['name' => $col['name']], $col);
        });

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
}
