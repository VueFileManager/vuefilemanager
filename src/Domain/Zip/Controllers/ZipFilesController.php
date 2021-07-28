<?php
namespace Domain\Zip\Controllers;

use Illuminate\Http\Request;
use STS\ZipStream\ZipStream;
use Domain\Files\Models\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Zip\Actions\ZipFilesAction;
use Domain\Traffic\Actions\RecordDownloadAction;

class ZipFilesController extends Controller
{
    public function __construct(
        private ZipFilesAction $zipFiles,
        private RecordDownloadAction $recordDownload,
    ) {
    }

    public function __invoke(
        Request $request,
    ): ZipStream {
        $files = File::whereUserId(Auth::id())
            ->whereIn('id', explode(',', $request->get('ids')))
            ->get();

        $zip = ($this->zipFiles)($files);

        ($this->recordDownload)(
            file_size: $zip->predictZipSize(),
            user_id: Auth::id(),
        );

        return $zip;
    }
}
