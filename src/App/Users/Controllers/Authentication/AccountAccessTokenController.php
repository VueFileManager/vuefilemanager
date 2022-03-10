<?php
namespace App\Users\Controllers\Authentication;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use App\Users\Requests\UserCreateAccessTokenRequest;

class AccountAccessTokenController extends Controller
{
    /**
     * Get all user tokens
     */
    public function index(): Response
    {
        return response(
            Auth::user()->tokens()->get(),
            200
        );
    }

    /**
     * Create user tokens
     */
    public function store(UserCreateAccessTokenRequest $request): Response
    {
        if (is_demo_account()) {
            return response(['plainTextToken' => Str::random(40)], 201);
        }

        $token = Auth::user()
            ->createToken(
                $request->input('name')
            );

        return response($token, 201);
    }

    /**
     * Delete user token
     */
    public function destroy(PersonalAccessToken $token): Response
    {
        abort_if(is_demo_account(), 204, 'Deleted!');

        if (Auth::id() !== $token->tokenable_id) {
            return response('Unauthorized', 401);
        }

        $token->delete();

        return response('Deleted!', 204);
    }
}
