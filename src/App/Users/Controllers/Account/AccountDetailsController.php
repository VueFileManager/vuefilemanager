<?php
namespace App\Users\Controllers\Account;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Users\Resources\UserResource;

class AccountDetailsController extends Controller
{
    /**
     * Get all user data for frontend
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(new UserResource(auth()->user()));
    }
}
