<?php
namespace App\Users\Controllers;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Users\Requests\CheckAccountRequest;

class AuthController extends Controller
{
    /**
     * Check if user account exist
     *
     * @param CheckAccountRequest $request
     * @return mixed
     */
    public function check_account(CheckAccountRequest $request)
    {
        // Get User
        $user = User::whereEmail($request->email)
            ->first();

        if (! $user) {
            return response(__t('user_not_fount'), 404);
        }

        return [
            'name'     => $user->settings->name,
            'avatar'   => $user->settings->avatar,
            'verified' => $user->email_verified_at ? true : false,
        ];
    }
}
