<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DeployController extends Controller
{
    /**
     * Get web hook payload and verify request
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function github(Request $request)   {

        if (($signature = $request->headers->get('X-Hub-Signature')) == null) {
            throw new BadRequestHttpException('Header not set');
        }

        $signature_parts = explode('=', $signature);

        if (count($signature_parts) != 2) {
            throw new BadRequestHttpException('signature has invalid format');
        }

        $known_signature = hash_hmac('sha1', $request->getContent(), config('app.deploy_secret'));

        if (! hash_equals($known_signature, $signature_parts[1])) {
            throw new UnauthorizedException('Could not verify request signature ' . $signature_parts[1]);
        }

        // Run deploying
        Artisan::call('deploy:production');

        Log::info('The GitHub webhook was accepted');

        return response('The GitHub webhook was accepted', 202);
    }
}
