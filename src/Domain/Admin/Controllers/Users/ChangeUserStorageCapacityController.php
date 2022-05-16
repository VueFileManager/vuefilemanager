<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
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
    ): JsonResponse {
        // Abort in demo mode
        if (isDemoAccount()) {
            return response()->json(new UserStorageResource($user));
        }

        $user
            ->limitations()
            ->update(
                $request->input('attributes')
            );

        return response()->json(new UserStorageResource($user));
    }
}
