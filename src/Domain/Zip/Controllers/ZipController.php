<?php
namespace Domain\Zip\Controllers;

use ZipStream\ZipStream;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Domain\Zip\Actions\ZipAction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Domain\Traffic\Actions\RecordDownloadAction;

class ZipController extends Controller
{
    public function __construct(
        public ZipAction $zip,
        public RecordDownloadAction $recordDownload,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(
        Request $request,
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

        // Zip items
        $zip = ($this->zip)($folders, $files);

        ($this->recordDownload)(
            $zip->predictZipSize(),
            auth()->id()
        );

        return $zip;
    }
}
