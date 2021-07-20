<?php


namespace App\Users\Controllers\Verification;


use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyEmailController extends Controller
{
    public function __invoke(
        string $id,
        Request $request,
    ): RedirectResponse|Response {

        if (! $request->hasValidSignature()) {
            return response('Invalid or expired url provided.', 401);
        }

        $user = User::find($id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/successfully-verified');
    }
}