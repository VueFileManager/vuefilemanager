<?php


namespace Domain\Zip\Controllers;


use App\Http\Controllers\Controller;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Zip\Actions\GetItemsListFromUrlParamAction;
use Domain\Zip\Actions\ZipAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use STS\ZipStream\ZipStream;

class ZipController extends Controller
{
    public function __construct(
        public ZipAction $zip,
        public RecordDownloadAction $recordDownload,
        public GetItemsListFromUrlParamAction $getItemsListFromUrlParam,
    ) {}

    public function __invoke(
        Request $request,
    ): ZipStream {
        $user_id = Auth::id();

        list($folders, $files) = ($this->getItemsListFromUrlParam)($user_id);

        $zip = ($this->zip)($folders, $files);

        ($this->recordDownload)(
            file_size: $zip->predictZipSize(),
            user_id: $user_id,
        );

        return $zip;
    }
}