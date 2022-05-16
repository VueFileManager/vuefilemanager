<?php
namespace App\Socialite\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteRedirectController extends Controller
{
    public function __invoke(
        string $provider
    ): JsonResponse {
        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'type'    => 'success',
            'message' => 'The user was successfully verified',
            'data'    => [
                'url' => $url,
            ],
        ]);
    }
}
