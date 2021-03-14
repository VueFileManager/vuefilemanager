<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\CheckAccountRequest;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserSettings;
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
        $user = User::whereEmail($request->email)
            ->first();

        if (! $user) {
            return response(__('vuefilemanager.user_not_fount'), 404);
        }

        return [
            'name'   => $user->settings->name,
            'avatar' => $user->settings->avatar,
        ];
    }
}
