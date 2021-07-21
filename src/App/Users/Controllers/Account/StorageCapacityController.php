<?php
namespace App\Users\Controllers\Account;

use Illuminate\Support\Facades\Auth;
use App\Users\Resources\UserStorageResource;

class StorageCapacityController
{
    public function __invoke(): UserStorageResource
    {
        return new UserStorageResource(
            Auth::user()
        );
    }
}
