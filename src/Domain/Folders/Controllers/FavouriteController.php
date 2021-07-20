<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Support\Demo\Actions\DemoService;

class FavouriteController extends Controller
{
    public function __construct(
        public DemoService $demo,
    ) {
    }

    /**
     * Add folder to user favourites
     * todo: pridat validator ako AddToFavouritesRequest
     */
    public function store(Request $request): Response
    {
        $user = Auth::user();

        foreach ($request->input('folders') as $id) {
            if (is_demo_account($user->email)) {
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
     */
    public function destroy(string $id): Response
    {
        $user = Auth::user();

        if (is_demo_account($user->email)) {
            return $this->demo->favourites($user);
        }

        // Remove folder from user favourites
        $user
            ->favouriteFolders()
            ->detach($id);

        // Return updated favourites
        return response($user->favouriteFolders, 204);
    }
}
