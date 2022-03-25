<?php
namespace Domain\Sharing\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Sharing\Resources\ShareResource;
use Domain\Sharing\Actions\SendViaEmailAction;
use Domain\Sharing\Requests\CreateShareRequest;
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
     * Generate file share link
     */
    public function store(
        CreateShareRequest $request,
        SendViaEmailAction $sendLinkToEmailAction,
    ): ShareResource {
        $item = get_item($request->input('type'), $request->input('id'));

        $this->authorize('owner', $item);

        $shared = Share::create([
            'password'     => $request->has('password') ? bcrypt($request->input('password')) : null,
            'type'         => $request->input('type') === 'folder' ? 'folder' : 'file',
            'is_protected' => $request->input('isPassword'),
            'permission'   => $request->input('permission') ?? null,
            'expire_in'    => $request->input('expiration') ?? null,
            'item_id'      => $request->input('id'),
            'user_id'      => Auth::id(),
        ]);

        // Send shared link via email
        if ($request->has('emails')) {
            $sendLinkToEmailAction->onQueue()->execute(
                emails: $request->input('emails'),
                token: $shared->token,
                user: $shared->user,
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
