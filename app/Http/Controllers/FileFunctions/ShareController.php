<?php

namespace App\Http\Controllers\FileFunctions;

use App\Http\Requests\Share\CreateShareRequest;
use App\Http\Requests\Share\UpdateShareRequest;
use App\Http\Resources\ShareResource;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Share;

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
        return new ShareResource(Share::create($options));
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
    public function destroy($token)
    {
        // Get sharing record
        $shared = Share::where('token', $token)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Delete shared record
        $shared->delete();

        // Done
        return response('Done!', 204);
    }
}
