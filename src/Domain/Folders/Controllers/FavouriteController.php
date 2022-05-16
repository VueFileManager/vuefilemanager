<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Folders\Requests\AddFolderToFavouritesRequest;

class FavouriteController extends Controller
{
    /**
     * Add folder to user favourites
     */
    public function store(
        AddFolderToFavouritesRequest $request
    ): JsonResponse {
        $successResponse = [
            'type'    => 'success',
            'message' => 'Folder was successfully added into your favourites folders',
        ];

        // Return success response for the demo response
        if (isDemoAccount()) {
            return response()->json($successResponse, 201);
        }

        // Add folder into user favourites
        foreach ($request->input('ids') as $id) {
            Auth::user()
                ->favouriteFolders()
                ->syncWithoutDetaching($id);
        }

        // Return success response
        return response()->json($successResponse, 201);
    }

    /**
     * Remove folder from user favourites
     */
    public function destroy(
        string $id
    ): JsonResponse {
        $successResponse = [
            'type'    => 'success',
            'message' => 'Folder was successfully removed from your favourites folders',
        ];

        if (isDemoAccount()) {
            return response()->json($successResponse, 201);
        }

        // Remove folder from user favourites
        Auth::user()
            ->favouriteFolders()
            ->detach($id);

        // Return updated favourites
        return response()->json($successResponse, 201);
    }
}
