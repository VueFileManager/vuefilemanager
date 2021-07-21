<?php
namespace Domain\Admin\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Routing\ResponseFactory;

class ResetUserPasswordController extends Controller
{
    /**
     * Send user password reset link
     */
    public function __invoke(User $user): Response
    {
        if (is_demo()) {
            return response('Done!', 204);
        }

        // Get password token
        $token = Password::getRepository()
            ->create($user);

        // Send user email
        $user->sendPasswordResetNotification($token);

        return response('Done!', 204);
    }
}
