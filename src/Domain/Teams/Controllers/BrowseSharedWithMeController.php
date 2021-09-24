<?php

namespace Domain\Teams\Controllers;

use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;
use Domain\Folders\Resources\FolderResource;
use Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BrowseSharedWithMeController
{
    public function __invoke($id): array
    {
        $id = Str::isUuid($id) ? $id : null;

        if ($id) {
            $folders = Folder::with(['parent:id,name'])
                ->where('parent_id', $id)
                ->sortable()
                ->get();

            $files = File::with(['parent:id,name'])
                ->where('parent_id', $id)
                ->sortable()
                ->get();
        }

        if (!$id) {
            $sharedFolderIds = DB::table('team_folder_members')
                ->where('user_id', Auth::id())
                ->pluck('parent_id');

            $folders = Folder::whereIn('id', $sharedFolderIds)
                ->sortable()
                ->get();
        }

        return [
            'root'       => $id ? new FolderResource(Folder::findOrFail($id)) : null,
            'folders'    => new FolderCollection($folders),
            'files'      => isset($files) ? new FilesCollection($files) : new FilesCollection([]),
            'teamFolder' => $id ? new FolderResource(Folder::findOrFail($id)->getLatestParent()) : null,
        ];
    }
}
