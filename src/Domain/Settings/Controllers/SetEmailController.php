<?php


namespace Domain\Settings\Controllers;


use Artisan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SetEmailController
{
    /**
     * Set new email credentials to .env file
     */
    public function __invoke(Request $request): Response
    {
        // TODO: pridat validator do requestu
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
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
        }

        return response('Done', 204);
    }
}