<?php

namespace App\Http\Controllers\Admin;

use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangeRoleRequest;
use App\Http\Requests\Admin\ChangeStorageCapacityRequest;
use App\Http\Requests\Admin\CreateUserByAdmin;
use App\Http\Requests\Admin\DeleteUserRequest;
use App\Http\Resources\UsersCollection;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserStorageResource;
use App\Http\Tools\Demo;
use App\Share;
use App\User;
use App\UserSettings;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
     * @param ChangeRoleRequest $request
     * @param $id
     * @return UserResource
     */
    public function change_role(ChangeRoleRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Demo preview
        if (env('APP_DEMO') && $id == 1) {
            return new UserResource($user);
        }

        $user->update($request->input('attributes'));

        return new UserResource($user);
    }

    /**
     * Change user storage capacity
     *
     * @param ChangeStorageCapacityRequest $request
     * @param $id
     * @return UserStorageResource
     */
    public function change_storage_capacity(ChangeStorageCapacityRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->settings()->update($request->input('attributes'));

        return new UserStorageResource($user);
    }

    /**
     * Send user password reset link
     *
     * @param $id
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function send_password_reset_email($id)
    {
        $user = User::findOrFail($id);

        // Demo preview
        if (env('APP_DEMO')) {
            return response('Done!', 204);
        }

        // Get password token
        $token = Password::getRepository()->create($user);

        // Send user email
        $user->sendPasswordResetNotification($token);

        return response('Done!', 204);
    }

    /**
     * Create new user by admin
     *
     * @param CreateUserByAdmin $request
     * @return UserResource
     */
    public function create_user(CreateUserByAdmin $request)
    {
        // Store avatar
        if ($request->hasFile('avatar')) {

            // Update avatar
            $avatar = store_avatar($request->file('avatar'), 'avatars');
        }

        // Create user
        $user = User::create([
            'avatar'   => $request->hasFile('avatar') ? $avatar : null,
            'name'     => $request->name,
            'role'     => $request->role,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create settings
        $settings = UserSettings::create([
            'user_id'          => $user->id,
            'storage_capacity' => $request->storage_capacity,
        ]);

        return new UserResource($user);
    }

    /**
     * Delete user with all user data
     *
     * @param DeleteUserRequest $request
     * @param $id
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function delete_user(DeleteUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        // Demo preview
        if (env('APP_DEMO')) {
            return response('Done!', 204);
        }

        // Check for self deleted account
        if ($user->id === Auth::id()) {
            abort(406, 'You can\'t delete your account');
        }

        // Validate user name
        if ($user->name !== $request->name) abort(403);

        $shares = Share::where('user_id', $user->id)->get();
        $files = FileManagerFile::withTrashed()
            ->where('user_id', $user->id)
            ->get();
        $folders = FileManagerFolder::withTrashed()
            ->where('user_id', $user->id)
            ->get();

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
