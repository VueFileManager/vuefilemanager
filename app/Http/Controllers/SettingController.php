<?php

namespace App\Http\Controllers;

use App\Setting;
use Artisan;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get table content
     *
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request) {

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
    public function set_email(Request $request) {

        // Get options
        $mail = collect([
            [
                'name'  => 'MAIL_DRIVER',
                'value' => $request->input('driver'),
            ],
            [
                'name'  => 'MAIL_HOST',
                'value' => $request->input('host'),
            ],
            [
                'name'  => 'MAIL_PORT',
                'value' => $request->input('port'),
            ],
            [
                'name'  => 'MAIL_USERNAME',
                'value' => $request->input('username'),
            ],
            [
                'name'  => 'MAIL_PASSWORD',
                'value' => $request->input('password'),
            ],
            [
                'name'  => 'MAIL_ENCRYPTION',
                'value' => $request->input('encryption'),
            ],
        ]);

        // Store mail options
        $mail->each(function ($col) {
            setEnvironmentValue($col['name'], $col['value']);
        });

        // Clear cache
        Artisan::call('config:clear');

        return response('Done', 204);
    }
}
