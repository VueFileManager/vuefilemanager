<?php
namespace Domain\Sharing\Controllers;

use Validator;
use Illuminate\Http\Request;
use Domain\Zipping\Models\Zip;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Sharing\Resources\ShareResource;
use Illuminate\Support\Facades\Notification;
use Domain\Sharing\Requests\CreateShareRequest;
use Domain\Sharing\Requests\UpdateShareRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Sharing\Notifications\SharedSendViaEmail;

class ShareController extends Controller
{
    /**
     * Get shared record
     *
     * @param Share $shared
     * @return ShareResource
     */
    public function show(Share $shared)
    {
        return new ShareResource(
            $shared
        );
    }

    /**
     * Generate file share link
     *
     * @param CreateShareRequest $request
     * @param $id
     * @return ShareResource
     */
    public function store(CreateShareRequest $request, $id)
    {
        // Create shared options
        $shared = Share::create([
            'password'     => $request->has('password') ? bcrypt($request->password) : null,
            'type'         => $request->type === 'folder' ? 'folder' : 'file',
            'is_protected' => $request->isPassword,
            'permission'   => $request->permission ?? null,
            'item_id'      => $id,
            'expire_in'    => $request->expiration ?? null,
            'user_id'      => Auth::id(),
        ]);

        // Send shared link via email
        if ($request->has('emails')) {
            foreach ($request->emails as $email) {
                Notification::route('mail', $email)->notify(
                    new SharedSendViaEmail($shared->token)
                );
            }
        }

        // Return created shared record
        return new ShareResource($shared);
    }

    /**
     * Update sharing
     *
     * @param UpdateShareRequest $request
     * @param $token
     * @return ShareResource
     */
    public function update(UpdateShareRequest $request, $token)
    {
        // Get sharing record
        $shared = Share::where('token', $token)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Update sharing record
        $shared->update([
            'permission'   => $request->permission,
            'is_protected' => $request->protected,
            'expire_in'    => $request->expiration,
            'password'     => $request->password ? bcrypt($request->password) : $shared->password,
        ]);

        // Return shared record
        return new ShareResource($shared);
    }

    /**
     * Delete sharing item
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        foreach ($request->tokens as $token) {
            // Get sharing record
            Share::where('token', $token)
                ->where('user_id', Auth::id())
                ->firstOrFail()
                ->delete();

            // Get zip record
            $zip = Zip::where('shared_token', $token)
                ->where('user_id', Auth::id())
                ->first();

            if ($zip) {
                $zip->delete();
            }
        }

        return response('Done!', 204);
    }

    /**
     * Send shared link via email to recipients
     *
     * @param $token
     * @param $request
     */
    public function send_to_emails_recipients(Request $request, $token)
    {
        // TODO: pridat validation request
        // Make validation of array of emails
        $validator = Validator::make($request->all(), [
            'emails.*' => 'required|email',
        ]);

        // Return error
        if ($validator->fails()) {
            abort(400, 'Bad email input');
        }

        // Send shared link via email
        if ($request->has('emails')) {
            foreach ($request->emails as $email) {
                Notification::route('mail', $email)
                    ->notify(new SharedSendViaEmail($token));
            }
        }

        return response('Done!', 204);
    }
}
