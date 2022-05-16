<?php
namespace App\Users\Controllers\Account;

use Illuminate\Http\JsonResponse;
use App\Users\Resources\UserStorageResource;

class StorageCapacityController
{
    public function __invoke(): JsonResponse
    {
        return response()->json(
            new UserStorageResource(auth()->user())
        );
    }
}
