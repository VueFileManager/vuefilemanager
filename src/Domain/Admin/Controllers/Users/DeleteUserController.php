<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Admin\Requests\DeleteUserRequest;
use Domain\Admin\Actions\DeleteUserDataAction;

class DeleteUserController extends Controller
{
    /**
     * Delete user with all user data
     */
    public function __invoke(
        DeleteUserRequest $request,
        User $user,
        DeleteUserDataAction $deleteUserData,
    ): JsonResponse {
        if (is_demo()) {
            return response()->json([
                'type'    => 'success',
                'message' => 'The user was successfully deleted',
            ]);
        }

        if ($user->subscription && $user->subscription->type === 'fixed' && $user->subscription->active()) {
            abort(
                response()->json([
                    'type'    => 'error',
                    'message' => "You can\'t delete this account since user has active subscription.",
                ], 202)
            );
        }

        if ($user->id === Auth::id()) {
            abort(
                response()->json([
                    'type'    => 'error',
                    'message' => "You can\'t delete your account",
                ], 406)
            );
        }

        if (trim($user->settings->name) !== $request->input('name')) {
            abort(
                response()->json([
                    'type'    => 'error',
                    'message' => 'The name you typed is wrong!',
                ], 403)
            );
        }

        $user->delete();

        // Delete all user data
        ($deleteUserData)($user);

        return response()->json([
            'type'    => 'success',
            'message' => 'The user was successfully deleted',
        ]);
    }
}
