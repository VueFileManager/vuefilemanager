<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserResource;
use Domain\Admin\Requests\ChangeRoleRequest;

class ChangeUserRoleController extends Controller
{
    public function __invoke(
        ChangeRoleRequest $request,
        User $user,
    ): UserResource {
        if (is_demo_account()) {
            return new UserResource($user);
        }

        // Update user role
        $user->role = $request->input('attributes.role');
        $user->update();

        return new UserResource($user);
    }
}
