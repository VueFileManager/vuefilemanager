<?php
namespace Domain\Zip\Controllers;

use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Zip\Actions\ZipFolderAction;

class ZipFolderController extends Controller
{
    public function __construct(
        private ZipFolderAction $zipFolder,
    ) {
    }

    public function __invoke(
        string $id,
    ): Response {
        $folder = Folder::whereUserId(Auth::id())
            ->where('id', $id);

        if (! $folder->exists()) {
            abort(404, "Requested folder doesn't exists.");
        }

        $zip = ($this->zipFolder)($id);

        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }
}
