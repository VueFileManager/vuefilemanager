<?php
namespace App\Users\Controllers\Authentication;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Users\Requests\CheckAccountRequest;

class CheckAccountController extends Controller
{
    /**
     * Check if user account exist
     */
    public function __invoke(
        CheckAccountRequest $request
    ): JsonResponse {
        $user = User::where('email', $request->input('email'))
            ->first();

        if (! $user) {
            return response()->json([
                'type'    => 'error',
                'message' => __t('user_not_fount'),
            ], 404);
        }

        return response()->json([
            'name'           => $user->settings->first_name,
            'avatar'         => $user->settings->avatar,
            'verified'       => $user->email_verified_at ? 1 : 0,
            'oauth_provider' => $user->password ? null : $user->oauth_provider,
        ]);
    }
}
