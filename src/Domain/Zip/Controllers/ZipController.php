<?php


namespace Domain\Zip\Controllers;


use App\Http\Controllers\Controller;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Zip\Actions\ZipAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use STS\ZipStream\ZipStream;

class ZipController extends Controller
{
    public function __construct(
        public ZipAction $zip,
        public RecordDownloadAction $recordDownload,
    ) {}

    public function __invoke(
        Request $request,
    ): ZipStream {

        $user_id = Auth::id();
        $items = explode(',', $request->get('items'));

        $itemList = collect($items)
            ->map(function ($chunk) {
                $items = explode('|', $chunk);

                return [
                    'id'   => $items[0],
                    'type' => $items[1],
                ];
            });

        $folderIds = $itemList
            ->where('type', 'folder')
            ->pluck('id');

        $fileIds = $itemList
            ->where('type', 'file')
            ->pluck('id');

        $folders = Folder::whereUserId($user_id)
            ->whereIn('id', $folderIds)
            ->get();

        $files = File::whereUserId($user_id)
            ->whereIn('id', $fileIds)
            ->get();

        $zip = ($this->zip)($folders, $files);

        ($this->recordDownload)(
            file_size: $zip->predictZipSize(),
            user_id: $user_id,
        );

        return $zip;
    }
}