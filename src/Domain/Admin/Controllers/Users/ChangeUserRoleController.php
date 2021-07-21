<?php
namespace Domain\Admin\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Users\Models\User;
use App\Users\Resources\UserResource;
use Domain\Admin\Requests\ChangeRoleRequest;

class ChangeUserRoleController extends Controller
{
    public function __invoke(
        ChangeRoleRequest $request,
        User $user,
    ): UserResource {
        if (is_demo_account($user->email)) {
            return new UserResource($user);
        }

        // Update user role
        $user->update([
            'role' => $request->input('attributes.role'),
        ]);

        return new UserResource($user);
    }
}
