<?php
namespace App\Users\Controllers\Verification;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(
        string $id,
    ): RedirectResponse | JsonResponse {
        if (! request()->hasValidSignature()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'Invalid or expired url provided.',
            ], 422);
        }

        $user = User::find($id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/successfully-verified');
    }
}
