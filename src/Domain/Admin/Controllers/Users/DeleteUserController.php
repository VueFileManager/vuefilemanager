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

        // TODO: secure deletion
        /*if ($user->subscription) {
            abort(202, "You can\'t delete this account while user have active subscription.");
        }*/

        if ($user->id === Auth::id()) {
            abort(406, "You can\'t delete your account");
        }

        if ($user->settings->name !== $request->name) {
            abort(403, 'The name you typed is wrong!');
        }

        $user->delete();

        // Delete all user data
        ($deleteUserData)($user);

        return response('Done.', 204);
    }
}
