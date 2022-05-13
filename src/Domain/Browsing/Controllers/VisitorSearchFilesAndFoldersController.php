<?php
namespace Domain\Browsing\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;
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
    ): JsonResponse {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        $query = remove_accents(
            $request->input('query')
        );

        // Get all children content
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_parent_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Prepare eloquent builder
        $folder = new Folder();
        $file = new File();

        // Prepare folders constrain
        $folderConstrain = $folder
            ->where('user_id', $shared->user_id)
            ->whereIn('id', $accessible_parent_ids);

        // Prepare files constrain
        $fileConstrain = $file
            ->where('user_id', $shared->user_id)
            ->whereIn('parent_id', $accessible_parent_ids);

        // Search files and folders
        $files = File::search($query)
            ->constrain($fileConstrain)
            ->get()
            ->take(3);

        // Map files and set public url for files
        $files->map(fn ($file) => $file->setSharedPublicUrl($shared->token));

        $folders = Folder::search($query)
            ->constrain($folderConstrain)
            ->get()
            ->take(3);

        // Collect folders and files to single array
        return response()->json([
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
        ]);
    }
}
