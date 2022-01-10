<?php
namespace App\Socialite\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteRedirectController extends Controller
{
    public function __invoke($provider)
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $url,
        ]);
    }
}
