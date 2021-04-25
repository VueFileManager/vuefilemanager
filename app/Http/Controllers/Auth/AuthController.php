<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckAccountRequest;

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
            'name' => $user->settings->name,
            'avatar' => $user->settings->avatar,
        ];
    }
}
