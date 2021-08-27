<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Models\Folder;
use Domain\Folders\Resources\FolderCollection;
use Illuminate\Support\Facades\Auth;
use Domain\Items\Requests\SearchRequest;

class SearchFilesAndFoldersController
{
    public function __invoke(
        SearchRequest $request
    ): array {
        $user_id = Auth::id();

        $query = remove_accents(
            $request->input('query')
        );

        // Search files id db
        $files = File::search($query)
            ->where('user_id', $user_id)
            ->get();

        $folders = Folder::search($query)
            ->where('user_id', $user_id)
            ->get();

        // Collect folders and files to single array
        return [
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
        ];
    }
}
