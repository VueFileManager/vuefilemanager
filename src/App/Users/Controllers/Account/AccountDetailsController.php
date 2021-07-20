<?php
namespace App\Users\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Users\Resources\UserResource;

class AccountDetailsController extends Controller
{
    /**
     * Get all user data for frontend
     */
    public function __invoke(): UserResource
    {
        return new UserResource(
            Auth::user()
        );
    }
}
