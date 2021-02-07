<?php

namespace App\Http\Controllers\FileFunctions;

use App\Http\Requests\Share\CreateShareRequest;
use App\Http\Requests\Share\UpdateShareRequest;
use App\Http\Resources\ShareResource;
use App\Notifications\SharedSendViaEmail;
use App\Zip;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Share;
use Validator;

class ShareController extends Controller
{
    /**
     * Get shared record
     *
     * @return ShareResource
     */
    public function show($token)
    {
        // Get record
        $shared = Share::where(DB::raw('BINARY `token`'), $token)
            ->firstOrFail();

        return new ShareResource($shared);
    }

    /**
     * Generate file share link
     *
     * @param CreateShareRequest $request
     * @return ShareResource
     */
    public function store(CreateShareRequest $request)
    {
        do {
            // Generate unique token
            $token = Str::random(16);

        } while (Share::where(DB::raw('BINARY `token`'), $token)->exists());

        // Create shared options
        $options = [
            'password'   => $request->has('password') ? Hash::make($request->password) : null,
            'type'       => $request->type === 'folder' ? 'folder' : 'file',
            'protected'  => $request->isPassword,
            'permission' => $request->permission,
            'item_id'    => $request->unique_id,
            'expire_in'  => $request->expiration,
            'user_id'    => Auth::id(),
            'token'      => $token,
        ];

        // Return created shared record
        $share = new ShareResource(Share::create($options));

        // Send shared link via email
        if ($request->has('emails')) {

            foreach ($request->emails as $email) {
                Notification::route('mail', $email)->notify(new SharedSendViaEmail($token));
            }
        }

        return $share;
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
            'permission' => $request->permission,
            'protected'  => $request->protected,
            'expire_in'  => $request->expiration,
            'password'   => $request->password ? Hash::make($request->password) : $shared->password,
        ]);

        // Return shared record
        return new ShareResource($shared);
    }

    /**
     * Delete sharing item
     *
     * @param $token
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        foreach ($request->input('tokens') as $token) {

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

        // Done
        return response('Done!', 204);
    }

    /**
     * Send shared link via email to recipients
     *
     * @param $token
     * @param $request
     */
    public function shared_send_via_email(Request $request, $token)
    {
        // Make validation of array of emails
        $validator = Validator::make($request->all(), [
            'emails.*' => 'required|email',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad email input');

        // Send shared link via email
        if($request->has('emails')) {
            foreach ($request->emails as $email) {
                Notification::route('mail', $email)->notify(new SharedSendViaEmail($token));
            }
        }

        return response('Done!', 204);
    }
}
