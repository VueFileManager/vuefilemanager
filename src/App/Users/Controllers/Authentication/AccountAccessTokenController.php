<?php
namespace App\Users\Controllers\Authentication;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Users\Requests\CreateAccessTokenRequest;

class AccountAccessTokenController extends Controller
{
    /**
     * Get all user tokens
     */
    public function index(): JsonResponse
    {
        return response()->json(
            Auth::user()->tokens()->get()
        );
    }

    /**
     * Create user tokens
     */
    public function store(
        CreateAccessTokenRequest $request
    ): JsonResponse {
        if (isDemoAccount()) {
            return response()->json([
                'plainTextToken' => Str::random(40),
            ], 201);
        }

        $token = Auth::user()
            ->createToken($request->input('name'));

        return response()->json($token, 201);
    }

    /**
     * Delete user token
     */
    public function destroy(
        PersonalAccessToken $token
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'The token was successfully deleted.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        if (Auth::id() !== $token->tokenable_id) {
            return response()->json([
                'type'    => 'error',
                'message' => 'You are not entitled to delete this token.',
            ], 401);
        }

        $token->delete();

        return response()->json($successMessage);
    }
}
