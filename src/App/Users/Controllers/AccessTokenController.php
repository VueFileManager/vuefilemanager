<?php


namespace App\Users\Controllers;


use App\Http\Controllers\Controller;
use App\Users\Requests\UserCreateAccessTokenRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokenController extends Controller
{
    /**
     * Get all user tokens
     */
    public function index(): Response
    {
        return response(
            Auth::user()->tokens()->get(), 200
        );
    }

    /**
     * Create user tokens
     */
    public function store(UserCreateAccessTokenRequest $request): Response
    {
        abort_if(is_demo_account('howdy@hi5ve.digital'), 201, [
            'name'           => 'token',
            'token'          => Str::random(40),
            'abilities'      => '["*"]',
            'tokenable_id'   => Str::uuid(),
            'updated_at'     => now(),
            'created_at'     => now(),
            'id'             => Str::random(40),
        ]);

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
        abort_if(is_demo_account('howdy@hi5ve.digital'), 204, 'Deleted!');

        if (Auth::id() !== $token->tokenable_id) {
            return response('Unauthorized', 401);
        }

        $token->delete();

        return response('Deleted!', 204);
    }
}