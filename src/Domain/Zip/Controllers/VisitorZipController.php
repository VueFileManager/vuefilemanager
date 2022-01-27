<?php
namespace Domain\Zip\Controllers;

use ZipStream\ZipStream;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Zip\Actions\ZipAction;
use App\Http\Controllers\Controller;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Domain\Zip\Actions\GetItemsListFromUrlParamAction;

class VisitorZipController extends Controller
{
    public function __construct(
        public GetItemsListFromUrlParamAction $getItemsListFromUrlParam,
        public ProtectShareRecordAction $protectShareRecord,
        public VerifyAccessToItemAction $verifyAccessToItem,
        public RecordDownloadAction $recordDownload,
        public ZipAction $zip,
    ) {
    }

    public function __invoke(
        Request $request,
        Share $shared,
    ): ZipStream {
        list($folders, $files) = ($this->getItemsListFromUrlParam)();

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
            file_size: $zip->predictZipSize(),
            user_id: $shared->user_id,
        );

        return $zip;
    }
}
