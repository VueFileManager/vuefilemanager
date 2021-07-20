<?php
namespace Domain\Sharing\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Domain\Zipping\Models\Zip;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Sharing\Resources\ShareResource;
use Domain\Sharing\Requests\CreateShareRequest;
use Domain\Sharing\Requests\UpdateShareRequest;
use Domain\Sharing\Actions\SendLinkToEmailAction;

class ShareController extends Controller
{
    /**
     * Get shared record
     */
    public function show(
        Share $shared,
    ): ShareResource {
        return new ShareResource($shared);
    }

    /**
     * Generate file share link
     */
    public function store(
        CreateShareRequest $request,
        SendLinkToEmailAction $sendLinkToEmailAction,
    ): ShareResource {
        $shared = Share::create([
            'password'     => $request->has('password')
                ? bcrypt($request->input('password'))
                : null,
            'type'         => $request->input('type') === 'folder'
                ? 'folder'
                : 'file',
            'is_protected' => $request->input('isPassword'),
            'permission'   => $request->input('permission') ?? null,
            'expire_in'    => $request->input('expiration') ?? null,
            'item_id'      => $request->input('id'),
            'user_id'      => Auth::id(),
        ]);

        // Send shared link via email
        if ($request->has('emails')) {
            ($sendLinkToEmailAction)(
                $request->input('emails'),
                $shared->token
            );
        }

        // Return created shared record
        return new ShareResource($shared);
    }

    /**
     * Update sharing
     */
    public function update(
        UpdateShareRequest $request,
        Share $shared,
    ): ShareResource {
        // Update sharing record
        $shared->update([
            'permission'   => $request->input('permission'),
            'is_protected' => $request->input('protected'),
            'expire_in'    => $request->input('expiration'),
            'password'     => $request->input('password')
                ? bcrypt($request->input('password'))
                : $shared->password,
        ]);

        // Return shared record
        return new ShareResource($shared);
    }

    /**
     * Delete sharing item
     */
    public function destroy(
        Request $request,
    ): Response {
        foreach ($request->input('tokens') as $token) {
            // Delete share record
            Share::where('token', $token)
                ->where('user_id', Auth::id())
                ->firstOrFail()
                ->delete();

            // Get zip record if exist
            $zip = Zip::where('shared_token', $token)
                ->where('user_id', Auth::id())
                ->first();

            if ($zip) {
                $zip->delete();
            }
        }

        return response('Done!', 204);
    }
}
