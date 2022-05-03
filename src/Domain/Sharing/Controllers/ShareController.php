<?php
namespace Domain\Sharing\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Sharing\Resources\ShareResource;
use Domain\Sharing\Requests\UpdateShareRequest;

class ShareController extends Controller
{
    /**
     * Get shared record
     */
    public function show(
        Share $share,
    ): ShareResource {
        return new ShareResource($share);
    }

    /**
     * Update sharing
     */
    public function update(
        UpdateShareRequest $request,
        Share $share,
    ): ShareResource {
        // Update sharing record
        $share->update([
            'permission'   => $request->input('permission'),
            'is_protected' => $request->input('protected'),
            'expire_in'    => $request->input('expiration') ?? null,
            'password'     => $request->input('password')
                ? bcrypt($request->input('password'))
                : $share->password,
        ]);

        // Return shared record
        return new ShareResource($share);
    }

    /**
     * Delete sharing item
     */
    public function destroy(): Response
    {
        foreach (request()->input('tokens') as $token) {
            // Delete share record
            Share::where('token', $token)
                ->where('user_id', Auth::id())
                ->firstOrFail()
                ->delete();
        }

        return response('Done.', 204);
    }
}
