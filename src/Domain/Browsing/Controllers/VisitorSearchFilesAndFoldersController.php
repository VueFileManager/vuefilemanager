<?php
namespace Domain\Browsing\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Domain\Sharing\Actions\ProtectShareRecordAction;

/**
 * Visitor search shared files
 */
class VisitorSearchFilesAndFoldersController extends Controller
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
    ) {
    }

    public function __invoke(
        Request $request,
        Share $shared,
    ): Collection {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        $query = remove_accents(
            $request->input('query')
        );

        // Search files id db
        $searched_files = File::search($query)
            ->where('user_id', $shared->user_id)
            ->get();
        $searched_folders = Folder::search($query)
            ->where('user_id', $shared->user_id)
            ->get();

        // Get all children content
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Filter files
        $files = $searched_files->filter(function ($file) use ($accessible_folder_ids, $shared) {
            // Set public urls
            $file->setPublicUrl($shared->token);

            // check if item is in accessible folders
            return in_array($file->folder_id, $accessible_folder_ids);
        });

        // Filter folders
        $folders = $searched_folders->filter(function ($folder) use ($accessible_folder_ids) {
            // check if item is in accessible folders
            return in_array($folder->id, $accessible_folder_ids);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }
}
