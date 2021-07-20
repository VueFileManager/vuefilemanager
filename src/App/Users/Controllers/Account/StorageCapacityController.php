<?php


namespace App\Users\Controllers\Account;


use App\Users\Resources\UserStorageResource;
use Illuminate\Support\Facades\Auth;

class StorageCapacityController
{
    public function __invoke(): UserStorageResource
    {
        return new UserStorageResource(
            Auth::user()
        );
    }
}