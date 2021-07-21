<?php
namespace Domain\Admin\Controllers\Users;

use App\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserStorageResource;

class ShowUserStorageCapacityController extends Controller
{
    /**
     * Get user storage details
     */
    public function __invoke(User $user): UserStorageResource
    {
        return new UserStorageResource($user);
    }
}
