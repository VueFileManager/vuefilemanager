<?php
namespace App\Http\Controllers\FileManager;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Services\DemoService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends Controller
{
    /**
     * FavouriteController constructor.
     */
    public function __construct()
    {
        $this->demo = resolve(DemoService::class);
    }

    /**
     * Add folder to user favourites
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // todo: pridat validator ako AddToFavouritesRequest

        foreach ($request->folders as $id) {
            // Get user & folder
            $user = Auth::user();

            if (is_demo($user->id)) {
                return $this->demo->favourites($user);
            }

            // Add folder to user favourites
            $user
                ->favouriteFolders()
                ->syncWithoutDetaching($id);
        }

        // Return updated favourites
        return response($user->favouriteFolders, 204);
    }

    /**
     * Remove folder from user favourites
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get user
        $user = Auth::user();

        if (is_demo($user->id)) {
            return $this->demo->favourites($user);
        }

        // Remove folder from user favourites
        $user->favouriteFolders()->detach($id);

        // Return updated favourites
        return response($user->favouriteFolders, 204);
    }
}
