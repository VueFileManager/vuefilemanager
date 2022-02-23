<?php
namespace Domain\Trash\Controllers;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DumpTrashController extends Controller
{
    public function __invoke(): Response
    {
        abort_if(
            is_demo_account(),
            204,
            'Done.'
        );

        $user_id = Auth::id();

        // Get files and folders
        $folders = Folder::onlyTrashed()
            ->where('user_id', $user_id)
            ->get();

        $files = File::onlyTrashed()
            ->where('user_id', $user_id)
            ->get();

        // Force delete folder
        $folders->each->forceDelete();

        // Force delete files
        foreach ($files as $file) {
            // Delete file
            Storage::delete("/files/$user_id/{$file->basename}");

            // Delete thumbnail if exist
            if ($file->thumbnail) {
                collect([
                    config('vuefilemanager.image_sizes.later'),
                    config('vuefilemanager.image_sizes.immediately'),
                ])->collapse()
                    ->each(function ($size) use ($file) {
                        Storage::delete("/files/$file->user_id/{$size['name']}-{$file->basename}");
                    });
            }

            // Delete file permanently
            $file->forceDelete();
        }

        // Return response
        return response('Done.', 204);
    }
}
