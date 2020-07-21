<?php

namespace App\Http\Controllers\FileFunctions;

use App\FileManagerFolder;
use App\Http\Tools\Demo;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * Add folder to user favourites
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|integer',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user & folder
        $user = Auth::user();
        $folder = FileManagerFolder::where('unique_id', $request->unique_id)->first();

        if (is_demo($user->id)) {
            return Demo::favourites($user);
        }

        // Check ownership
        if ($folder->user_id !== $user->id) abort(403);

        // Add folder to user favourites
        $user->favourite_folders()->syncWithoutDetaching($request->unique_id);

        // Return updated favourites
        return $user->favourite_folders;
    }

    /**
     * Remove folder from user favourites
     *
     * @param $unique_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($unique_id)
    {
        // Get user
        $user = Auth::user();

        if (is_demo($user->id)) {
            return Demo::favourites($user);
        }

        // Remove folder from user favourites
        $user->favourite_folders()->detach($unique_id);

        // Return updated favourites
        return $user->favourite_folders;
    }
}
