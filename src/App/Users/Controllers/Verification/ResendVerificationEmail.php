<?php
namespace App\Users\Controllers\Verification;

use App\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ResendVerificationEmail extends Controller
{
    public function __invoke(
        Request $request
    ): Response {
        $user = User::where('email', $request->input('email'))
            ->first();

        if ($user->hasVerifiedEmail()) {
            return response('Email was already verified.', 204);
        }

        $user->sendEmailVerificationNotification();

        return response('Email verification link sent to your email', 204);
    }
}
