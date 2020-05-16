<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class DeployController extends Controller
{
    /**
     * Get web hook payload and verify request
     *
     * @param Request $request
     */
    public function deploy(Request $request)   {

        $githubPayload = $request->getContent();
        $localToken = config('app.deploy_secret');

        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

        if (hash_equals( $request->header('X-Hub-Signature'), $localHash)) {

            Artisan::call('deploy:production');
        }
    }
}
