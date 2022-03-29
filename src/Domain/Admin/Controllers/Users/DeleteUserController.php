<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\Response;
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
    ): Response {
        if (is_demo()) {
            return response('Done.', 204);
        }

        if ($user->subscription && $user->subscription->active()) {
            abort(202, "You can\'t delete this account since user has active subscription.");
        }

        if ($user->id === Auth::id()) {
            abort(406, "You can\'t delete your account");
        }

        if (trim($user->settings->name) !== $request->input('name')) {
            abort(403, 'The name you typed is wrong!');
        }

        $user->delete();

        // Delete all user data
        ($deleteUserData)($user);

        return response('Done.', 204);
    }
}
