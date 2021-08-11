<?php
namespace Domain\Zip\Controllers;

use Illuminate\Http\Request;
use STS\ZipStream\ZipStream;
use Domain\Zip\Actions\ZipAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Zip\Actions\GetItemsListFromUrlParamAction;

class ZipController extends Controller
{
    public function __construct(
        public ZipAction $zip,
        public RecordDownloadAction $recordDownload,
        public GetItemsListFromUrlParamAction $getItemsListFromUrlParam,
    ) {
    }

    public function __invoke(
        Request $request,
    ): ZipStream {
        $user_id = Auth::id();

        // Get list of folders and files from requested url parameter
        list($folders, $files) = ($this->getItemsListFromUrlParam)($user_id);

        // Zip items
        $zip = ($this->zip)($folders, $files);

        ($this->recordDownload)(
            file_size: $zip->predictZipSize(),
            user_id: $user_id,
        );

        return $zip;
    }
}
