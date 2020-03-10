<?php

namespace App\Http\Controllers\PrivateCLoud;

use App\User;
use ByteUnits\Metric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class UserAccountController extends Controller
{

    /**
     * Update user profile
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update_profile(Request $request) {
        // TODO: validacia

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

        $user = Auth::user();

        $user->password = Hash::make($request->input('password'));
        $user->save();

    }

    /**
     * Get all user data to frontend
     *
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function user(Request $request)
    {
        // Get User
        $user = User::with(['favourites', 'latest_uploads'])
            ->where('id', Auth::id())
            ->first();

        // TODO: dat do configu maximalnu kapacitu pre usera
        return [
            'user'           => $user->only(['name', 'email', 'avatar']),
            'favourites'     => $user->favourites->makeHidden(['pivot']),
            'latest_uploads' => $user->latest_uploads->makeHidden(['user_id', 'basename']),
            'storage'        => [
                'used'       => Metric::bytes($user->used_capacity)->format(),
                'capacity'   => format_gigabytes(10),
                'percentage' => get_storage_fill_percentage($user->used_capacity, 10),
            ],
        ];
    }

    /**
     * Add folder to user favourites
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function add_to_favourites(Request $request)
    {
        // TODO: validation

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
        // TODO: validation

        // Get user
        $user = Auth::user();

        // Remove folder from user favourites
        $user->favourites()->detach($request->unique_id);

        // Return updated favourites
        return $user->favourites->makeHidden(['pivot']);
    }
}
