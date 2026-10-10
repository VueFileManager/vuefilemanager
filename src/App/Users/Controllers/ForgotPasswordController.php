<?php
namespace App\Users\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $response === Password::RESET_LINK_SENT
            ? response(['message' => Lang::get($response)])
            : response(['message' => Lang::get($response)], 422);
    }
}
