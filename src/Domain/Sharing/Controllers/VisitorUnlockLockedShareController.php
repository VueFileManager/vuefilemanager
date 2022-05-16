<?php
namespace Domain\Sharing\Controllers;

use Exception;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Domain\Sharing\Resources\ShareResource;
use Domain\Sharing\Requests\AuthenticateShareRequest;

class VisitorUnlockLockedShareController extends Controller
{
    /**
     * Check Password for protected item
     *
     * @throws Exception
     */
    public function __invoke(
        AuthenticateShareRequest $request,
        Share $shared,
    ): Response {
        // Check password
        if (Hash::check($request->input('password'), $shared->password)) {
            $cookie = json_encode([
                'token'         => $shared->token,
                'authenticated' => true,
            ]);

            // Return authorize token with shared options
            return response(new ShareResource($shared))
                ->cookie('share_session', $cookie, 43200);
        }

        return response(__t('incorrect_password'), 401);
    }
}
