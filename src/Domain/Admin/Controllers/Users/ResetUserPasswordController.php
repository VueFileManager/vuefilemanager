<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class ResetUserPasswordController extends Controller
{
    /**
     * Send user password reset link
     */
    public function __invoke(User $user): JsonResponse
    {
        $message = [
            'type'    => 'success',
            'message' => 'The password reset link was send succesfully',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        // Get password token
        $token = Password::getRepository()
            ->create($user);

        // Send user email
        $user->sendPasswordResetNotification($token);

        return response()->json($message);
    }
}
