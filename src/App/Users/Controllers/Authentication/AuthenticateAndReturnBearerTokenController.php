<?php
namespace App\Users\Controllers\Authentication;

use Hash;
use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Users\Requests\LoginUserRequest;
use Illuminate\Validation\ValidationException;

class AuthenticateAndReturnBearerTokenController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(
        LoginUserRequest $request
    ): JsonResponse {
        // Get user
        $user = User::where('email', $request->input('email'))
            ->firstOrFail();

        // Check the credentials
        if (! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.'], ]);
        }

        // Create access token
        $token = $user->createToken('login');

        // Return token
        return response()->json([
            'type'    => 'success',
            'message' => 'You were successfully authenticated',
            'data'    => [
                'token' => $token->plainTextToken,
            ],
        ]);
    }
}
