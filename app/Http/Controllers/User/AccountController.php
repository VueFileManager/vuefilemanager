<?php

namespace App\Http\Controllers\User;

use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\StorageDetailResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserStorageResource;
use App\Http\Tools\Demo;
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
     * @return UserResource
     */
    public function user()
    {
        return new UserResource(
            Auth::user()
        );
    }

    /**
     * Get storage details
     *
     * @return UserStorageResource
     */
    public function storage()
    {
        return new UserStorageResource(
            Auth::user()
        );
    }

    /**
     * Get user invoices
     *
     * @return InvoiceCollection
     */
    public function invoices() {
        return new InvoiceCollection(
            Auth::user()->invoices()
        );
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
            'avatar' => 'file',
            'name'   => 'string',
            'value'  => 'string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Update data
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
     * Update user settings relationship
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_user_settings(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name'   => 'string',
            'value'  => 'string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Update text data
        $user->settings->update(make_single_input($request));

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

        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Change and store new password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response('Changed!', 204);
    }
}
