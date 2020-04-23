<?php

namespace App\Http\Controllers\FileFunctions;

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
