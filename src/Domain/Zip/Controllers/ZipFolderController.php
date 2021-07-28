<?php
namespace Domain\Zip\Controllers;

use STS\ZipStream\ZipStream;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Zip\Actions\ZipFolderAction;
use Domain\Traffic\Actions\RecordDownloadAction;

class ZipFolderController extends Controller
{
    public function __construct(
        private ZipFolderAction $zipFolder,
        private RecordDownloadAction $recordDownload,
    ) {
    }

    public function __invoke(
        string $id,
    ): ZipStream {
        $folder = Folder::whereUserId(Auth::id())
            ->where('id', $id);

        if (! $folder->exists()) {
            response("Requested folder doesn't exists.", 404);
        }

        $zip = ($this->zipFolder)($id);

        ($this->recordDownload)(
            file_size: $zip->predictZipSize(),
            user_id: Auth::id(),
        );

        return $zip;
    }
}
