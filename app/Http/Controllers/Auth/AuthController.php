<?php

namespace App\Http\Controllers\Auth;

use App\ClientProfile;
use App\Models\User\UserAttribute;
use App\Models\User\UserNotificationSetting;
use App\ProviderProfile;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Check if user account exist
     *
     * @param Request $request
     * @return mixed
     */
    public function check_account(Request $request) {

        // Validate request
        $request->validate([
            'email'    => ['required', 'string', 'email'],
        ]);

        // Get User
        $user = User::where('email', $request->input('email'))->select(['name', 'avatar'])->first();

        // Return user info
        if ($user) return [
            'name' => $user->name,
            'avatar' => $user->avatar,
        ];

        // Abort with 404, user not found
        return abort('404', 'We can\'t find a user with that e-mail address.');
    }
    /**
     * Login user
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $response = Route::dispatch(self::make_request($request));

        if ($response->isSuccessful()) {

            $data = json_decode($response->content(), true);

            return response('Login Successfull!', 200)->cookie('token', $data['access_token'], 43200);
        } else {

            return $response;
        }
    }

    /**
     * Register user
     *
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        // Check if account registration is enabled
        if (! config('vuefilemanager.registration') ) abort(401);

        // Validate request
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Create user
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $response = Route::dispatch(self::make_request($request));

        if ($response->isSuccessful()) {

            $data = json_decode($response->content(), true);

            return response('Register Successfull!', 200)->cookie('token', $data['access_token'], 43200);
        } else {

            return $response;
        }
    }

    /**
     * Logout user entity
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Get user tokens and remove it
        auth()->user()->tokens()->each(function ($token) {

            // Remove tokens
            $token->delete();
        });

        return response('Logout successfull', 200)->cookie('token', '', -1);
    }

    /**
     * Make request for get user token
     *
     * @param Request $request
     * @param string $provider
     * @return Request
     */
    private static function make_request(Request $request)
    {
        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => '',
        ]);

        return Request::create(url('/oauth/token'), 'POST', $request->all());
    }
}
