<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserStorageResource;
use Domain\Admin\Requests\ChangeStorageCapacityRequest;

class ChangeUserStorageCapacityController extends Controller
{
    /**
     * Change user storage capacity
     */
    public function __invoke(
        ChangeStorageCapacityRequest $request,
        User $user,
    ): UserStorageResource {
        // Abort in demo mode
        if (is_demo_account()) {
            return new UserStorageResource($user);
        }

        $user
            ->limitations()
            ->update(
                $request->input('attributes')
            );

        return new UserStorageResource($user);
    }
}
