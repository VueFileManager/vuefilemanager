<?php
namespace App\Users\Controllers\Verification;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Users\Requests\ResendVerificationEmailRequest;

class ResendVerificationEmailController extends Controller
{
    public function __invoke(
        ResendVerificationEmailRequest $request
    ): JsonResponse {
        $user = User::where('email', $request->input('email'))
            ->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'Email was already verified.',
            ], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'type'    => 'success',
            'message' => 'Email verification link was sent to the email',
        ]);
    }
}
