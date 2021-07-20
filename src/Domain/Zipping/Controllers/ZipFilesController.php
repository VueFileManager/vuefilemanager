<?php
namespace Domain\Zipping\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Zipping\Actions\ZipFilesAction;

class ZipFilesController extends Controller
{
    public function __invoke(
        Request $request,
        ZipFilesAction $zipFiles,
    ): Response {
        $files = File::whereUserId(Auth::id())
            ->whereIn('id', $request->input('items'))
            ->get();

        $zip = ($zipFiles)($files);

        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }
}
