<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    /**
     * Add folder to user favourites
     * todo: pridat validator ako AddToFavouritesRequest
     */
    public function store(Request $request): Response|Collection
    {
        $user = Auth::user();

        foreach ($request->input('folders') as $id) {
            if (is_demo_account()) {
                return $user->favouriteFolders->makeHidden(['pivot']);
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
     */
    public function destroy(string $id): Response|Collection
    {
        $user = Auth::user();

        if (is_demo_account()) {
            return $user->favouriteFolders->makeHidden(['pivot']);
        }

        // Remove folder from user favourites
        $user
            ->favouriteFolders()
            ->detach($id);

        // Return updated favourites
        return response($user->favouriteFolders, 204);
    }
}
