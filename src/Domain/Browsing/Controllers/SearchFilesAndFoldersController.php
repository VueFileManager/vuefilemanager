<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Domain\Items\Requests\SearchRequest;

class SearchFilesAndFoldersController
{
    public function __invoke(
        SearchRequest $request
    ): Collection {
        $user_id = Auth::id();

        $query = remove_accents(
            $request->input('query')
        );

        // Search files id db
        $searched_files = File::search($query)
            ->where('user_id', $user_id)
            ->get();

        $searched_folders = Folder::search($query)
            ->where('user_id', $user_id)
            ->get();

        // Collect folders and files to single array
        return collect([$searched_folders, $searched_files])
            ->collapse()
            ->take(10);
    }
}
