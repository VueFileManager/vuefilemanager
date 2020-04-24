<?php

namespace App\Http\Controllers\FileFunctions;

use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Share;

class ShareController extends Controller
{

    /**
     * Generate file share link
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        // TODO: validation

        do {
            // Generate unique token
            $token = Str::random(16);

        } while (Share::where(DB::raw('BINARY `token`'), $token)->exists());

        // Create shared options
        $options = [
            'token'      => $token,
            'user_id'    => Auth::id(),
            'item_id'    => $request->unique_id,
            'permission' => $request->permission,
            'protected'  => $request->isPassword,
            'type'       => $request->type === 'folder' ? 'folder' : 'file',
            'password'   => $request->has('password') ? Hash::make($request->password) : null,
        ];

        // Store shared item
        $shared = Share::create($options);

        // Return shared record
        return Arr::except($shared, ['password', 'user_id', 'updated_at', 'created_at']);
    }

    /**
     * Update sharing
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        // TODO: validacia

        // Get sharing record
        $shared = Share::where('token', $request->get('token'))->firstOrFail();

        // Update sharing record
        $shared->update([
            'permission' => $request->permission,
            'protected'  => $request->isProtected,
            'password'   => $request->has('password') ? Hash::make($request->password) : $shared->password,
        ]);

        // Return shared record
        return Arr::except($shared, ['password', 'user_id', 'updated_at', 'created_at']);
    }

    /**
     * Delete sharing item
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Get sharing record
        $shared = Share::where('token', $request->get('token'))->firstOrFail();

        // Delete shared record
        $shared->delete();

        // Done
        return response('Done!', 202);
    }
}
