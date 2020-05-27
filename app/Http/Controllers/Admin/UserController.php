<?php

namespace App\Http\Controllers\Admin;

use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserStorageResource;
use App\Share;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Storage;

class UserController extends Controller
{
    /**
     * Get user details
     *
     * @param $id
     * @return UserResource
     */
    public function details($id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /**
     * Get user storage details
     *
     * @param $id
     * @return UserStorageResource
     */
    public function storage($id)
    {
        return new UserStorageResource(User::findOrFail($id));
    }

    /**
     * Get all users
     *
     * @return UsersCollection
     */
    public function users()
    {
        return new UsersCollection(User::all());
    }

    /**
     * Change user role
     *
     * @param Request $request
     * @param $id
     * @return UserResource
     */
    public function change_role(Request $request, $id)
    {
        // TODO: validacia

        $user = User::findOrFail($id);

        $user->update($request->input('attributes'));

        return new UserResource($user);
    }

    /**
     * Change user storage capacity
     *
     * @param Request $request
     * @param $id
     * @return UserStorageResource
     */
    public function change_storage_capacity(Request $request, $id)
    {
        // TODO: validacia

        $user = User::findOrFail($id);

        $user->settings()->update($request->input('attributes'));

        return new UserStorageResource($user);
    }

    /**
     * Send user password reset link
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function send_password_reset_email($id)
    {
        $user = User::findOrFail($id);

        $user->sendPasswordResetNotification(Str::random(60));

        return response('Done!', 204);
    }

    /**
     * Delete user with all user data
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function delete_user(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Check for self deleted account
        if ($user->id === Auth::id()) {
            abort(406, 'You can\'t delete your account');
        }

        // Validate user name
        if ( $user->name !== $request->name) abort(403);

        $files = FileManagerFile::where('user_id', $user->id)->get();
        $shares = Share::where('user_id', $user->id)->get();
        $folders = FileManagerFolder::where('user_id', $user->id)->get();

        // Remove all files and thumbnails
        $files->each(function ($file) {

            // Delete file
            Storage::delete('/file-manager/' . $file->basename);

            // Delete thumbnail if exist
            if (!is_null($file->thumbnail)) {
                Storage::delete('/file-manager/' . $file->getOriginal('thumbnail'));
            }

            // Delete file permanently
            $file->forceDelete();
        });

        // Remove avatar
        if ($user->avatar) {
            Storage::delete('/avatars/' . $user->avatar);
        }

        // Remove folders & shares
        $folders->each->forceDelete();
        $shares->each->forceDelete();

        // Remove favourites
        $user->settings->delete();
        $user->favourites()->sync([]);

        // Delete user
        $user->delete();

        return response('Done!', 204);
    }
}
