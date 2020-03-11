<?php

namespace App\Http\Controllers;

use App\User;
use ByteUnits\Metric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class UserAccountController extends Controller
{
    /**
     * Get all user data to frontend
     *
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function user()
    {
        $user_id = Auth::id();

        // Get User
        $user = User::with(['favourites', 'latest_uploads'])->where('id', $user_id)->first();

        return [
            'user'           => $user->only(['name', 'email', 'avatar']),
            'favourites'     => $user->favourites->makeHidden(['pivot']),
            'latest_uploads' => $user->latest_uploads->makeHidden(['user_id', 'basename']),

            'storage'        => [
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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

        return response('Saved!', 200);
    }

    /**
     * Change user password
     *
     * @param Request $request
     * @return array
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
    }

    /**
     * Add folder to user favourites
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function add_to_favourites(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|integer',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user = Auth::user();

        // Add folder to user favourites
        $user->favourites()->attach($request->unique_id);

        // Return updated favourites
        return $user->favourites->makeHidden(['pivot']);
    }

    /**
     * Remove folder from user favourites
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function remove_from_favourites(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|integer',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user = Auth::user();

        // Remove folder from user favourites
        $user->favourites()->detach($request->unique_id);

        // Return updated favourites
        return $user->favourites->makeHidden(['pivot']);
    }
}
