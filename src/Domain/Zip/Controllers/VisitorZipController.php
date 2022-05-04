<?php
namespace Domain\Zip\Controllers;

use ZipStream\ZipStream;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Zip\Actions\ZipAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

class VisitorZipController extends Controller
{
    public function __construct(
        public ProtectShareRecordAction $protectShareRecord,
        public VerifyAccessToItemAction $verifyAccessToItem,
        public RecordDownloadAction $recordDownload,
        public ZipAction $zip,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(
        Request $request,
        Share $shared,
    ): ZipStream {
        $items = extractItemsFromGetAttribute($request->get('items'));

        // Validate items GET attribute
        Validator::make(['items' => $items->toArray()], [
            'items'        => 'array',
            'items.*.id'   => 'required|uuid',
            'items.*.type' => 'required|string',
        ])->validate();

        // Get list of folders and files from requested url parameter
        $folderIds = $items
            ->where('type', 'folder')
            ->pluck('id');

        $fileIds = $items
            ->where('type', 'file')
            ->pluck('id');

        $folders = Folder::query()
            ->whereIn('id', $folderIds)
            ->get();

        $files = File::query()
            ->whereIn('id', $fileIds)
            ->get();

        // Check access to requested folders
        if ($folders->isNotEmpty()) {
            $folders->each(
                fn ($folder) => ($this->verifyAccessToItem)($folder->id, $shared)
            );
        }

        // Check access to requested files
        if ($files->isNotEmpty()) {
            $file_parent_folders = File::whereUserId($shared->user_id)
                ->whereIn('id', $files->pluck('id'))
                ->get()
                ->pluck('parent_id')
                ->toArray();

            // Check access to requested directory
            ($this->verifyAccessToItem)($file_parent_folders, $shared);
        }

        // Zip items
        $zip = ($this->zip)($folders, $files, $shared);

        ($this->recordDownload)(
            $zip->predictZipSize(),
            $shared->user_id
        );

        return $zip;
    }
}
