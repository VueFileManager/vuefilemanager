<?php
namespace Domain\Zipping\Controllers;

use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Zipping\Actions\ZipFolderAction;

class ZipFolderController extends Controller
{
    public function __invoke(
        string $id,
        ZipFolderAction $zipFolder,
    ): Response {
        $folder = Folder::whereUserId(Auth::id())
            ->where('id', $id);

        if (! $folder->exists()) {
            abort(404, "Requested folder doesn't exists.");
        }

        $zip = ($zipFolder)($id);

        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }
}
