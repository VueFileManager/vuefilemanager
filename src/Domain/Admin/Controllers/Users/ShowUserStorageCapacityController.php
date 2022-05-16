<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserStorageResource;

class ShowUserStorageCapacityController extends Controller
{
    /**
     * Get user storage details
     */
    public function __invoke(User $user): JsonResponse
    {
        return response()->json(new UserStorageResource($user));
    }
}
