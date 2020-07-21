<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\CheckAccountRequest;
use App\Setting;
use App\User;
use App\UserSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{

    /**
     * Check if user account exist
     *
     * @param Request $request
     * @return mixed
     */
    public function check_account(CheckAccountRequest $request)
    {
        // Get User
        $user = User::where('email', $request->input('email'))->select(['name', 'avatar'])->first();

        // Return user info
        if ($user) return [
            'name'   => $user->name,
            'avatar' => $user->avatar,
        ];

        // Abort with 404, user not found
        return abort('404', __('vuefilemanager.user_not_fount'));
    }

    /**
     * Login user
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $response = Route::dispatch(self::make_login_request($request));

        if ($response->isSuccessful()) {

            $data = json_decode($response->content(), true);

            return response('Login Successfull!', 200)->cookie('access_token', $data['access_token'], 43200);
        }

        return $response;
    }

    /**
     * Register user
     *
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $settings = Setting::whereIn('name', ['storage_default', 'registration'])->pluck('value', 'name');

        // Check if account registration is enabled
        if (! intval($settings['registration'])) abort(401);

        // Validate request
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create settings
        UserSettings::forceCreate([
            'user_id'          => $user->id,
            'storage_capacity' => $settings['storage_default'],
        ]);

        $response = Route::dispatch(self::make_login_request($request));

        if ($response->isSuccessful()) {

            $data = json_decode($response->content(), true);

            return response('Register Successfull!', 200)->cookie('access_token', $data['access_token'], 43200);
        }

        return $response;
    }

    /**
     * Logout user entity
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return response('Logout successfull', 204)
                ->cookie('access_token', '', -1);
        }

        // Get user tokens and remove it
        auth()->user()->tokens()->each(function ($token) {

            // Remove tokens
            $token->delete();
        });

        return response('Logout successful', 204)
            ->cookie('access_token', '', -1);
    }

    /**
     * Make login request for get access token
     *
     * @param Request $request
     * @return Request
     */
    private static function make_login_request($request)
    {
        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => 'master',
        ]);

        return Request::create(url('/oauth/token'), 'POST', $request->all());
    }
}
