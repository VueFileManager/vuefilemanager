<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Requests\Share\CreateShareRequest;
use App\Http\Requests\Share\UpdateShareRequest;
use App\Http\Resources\ShareResource;
use App\Models\Share;
use App\Models\Zip;
use App\Notifications\SharedSendViaEmail;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Validator;

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
            'password'     => $request->has('password') ? Hash::make($request->password) : null,
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
            'password'     => $request->password ? Hash::make($request->password) : $shared->password,
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
