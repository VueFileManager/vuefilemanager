<?php
namespace Domain\Trash\Controllers;

use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DumpTrashController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $successMessage = [
            'type'    => 'success',
            'message' => 'The trash was successfully dumped.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        // Delete folders
        Folder::onlyTrashed()
            ->where('user_id', auth()->id())
            ->cursor()
            ->each(fn ($folder) => $folder->forceDelete());

        // Delete files
        File::onlyTrashed()
            ->where('user_id', auth()->id())
            ->cursor()
            ->each(function ($file) {
                // Delete file
                Storage::delete("/files/$file->user_id/$file->basename");

                // Delete thumbnail if exist
                if ($file->thumbnail) {
                    collect([
                        config('vuefilemanager.image_sizes.later'),
                        config('vuefilemanager.image_sizes.immediately'),
                    ])->collapse()
                        ->each(function ($size) use ($file) {
                            Storage::delete("/files/$file->user_id/{$size['name']}-$file->basename");
                        });
                }

                // Delete file permanently
                $file->forceDelete();
            });

        // Return response
        return response()->json($successMessage);
    }
}
