<?php

namespace App\Http\Controllers\User;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use ByteUnits\Metric;
use App\User;

class AccountController extends Controller
{
    /**
     * Get all user data to frontend
     *
     * @return array
     */
    public function user()
    {
        // Get User
        $user = User::with(['favourites', 'latest_uploads'])
            ->where('id', Auth::id())
            ->first();

        return [
            'user'           => $user->only(['name', 'email', 'avatar']),
            'favourites'     => $user->favourites->makeHidden(['pivot']),
            'latest_uploads' => $user->latest_uploads->makeHidden(['user_id', 'basename']),
            'storage' => [
                'used'       => Metric::bytes($user->used_capacity)->format(),
                'capacity'   => format_gigabytes(config('vuefilemanager.user_storage_capacity')),
                'percentage' => get_storage_fill_percentage($user->used_capacity, config('vuefilemanager.user_storage_capacity')),
            ],
        ];
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'avatar'  => 'file',
            '_method' => 'string',
            'name'    => 'string',
            'value'   => 'string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user = Auth::user();

        if ($request->hasFile('avatar')) {

            // Update avatar
            $avatar = store_avatar($request->file('avatar'), 'avatars');

            // Update data
            $user->update(['avatar' => $avatar]);

        } else {

            // Update text data
            $user->update(make_single_input($request));
        }

        return response('Saved!', 204);
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function change_password(Request $request)
    {
        // Validate request
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Get user
        $user = Auth::user();

        // Change and store new password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response('Changed!', 204);
    }
}
