<?php

namespace App\Http\Controllers\FileFunctions;

use App\Http\Tools\Demo;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;

class TrashController extends Controller
{
    /**
     * Empty user trash
     *
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function clear()
    {
        // Get user id
        $user_id = Auth::id();

        if (is_demo($user_id)) {
            return Demo::response_204();
        }

        // Get files and folders
        $folders = Folder::onlyTrashed()->where('user_id', $user_id)->get();
        $files = File::onlyTrashed()->where('user_id', $user_id)->get();

        // Force delete folder
        $folders->each->forceDelete();

        // Force delete files
        foreach ($files as $file) {

            // Delete file
            Storage::delete("/files/$user_id/{$file->basename}");

            // Delete thumbnail if exist
            if ($file->thumbnail) {
                Storage::delete("/files/$user_id/{$file->getRawOriginal('thumbnail')}");
            }

            // Delete file permanently
            $file->forceDelete();
        }

        // Return response
        return response('Done!', 204);
    }

    /**
     * Restore item from trash
     *
     * @param Request $request
     * @param $unique_id
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->input('data'), [
            '*.type'      => 'required|string',
            '*.unique_id' => 'integer',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user id
        $user_id = Auth::id();

        if (is_demo($user_id)) {
            return Demo::response_204();
        }

        foreach($request->input('data') as $restore_item) {
    
            // Get folder
            if ($restore_item['type'] === 'folder') {

                // Get folder
                $item = Folder::onlyTrashed()
                    ->where('user_id', $user_id)
                    ->where('unique_id', $restore_item['unique_id'])
                    ->first();

                // Restore item to home directory
                if ($request->has('to_home') && $request->to_home) {
                    $item->parent_id = 0;
                    $item->save();
                }
            } else {

                // Get item
                $item = File::onlyTrashed()
                    ->where('user_id', $user_id)
                    ->where('unique_id', $restore_item['unique_id'])
                    ->first();

                // Restore item to home directory
                if ($request->has('to_home') && $request->to_home) {
                    $item->folder_id = 0;
                    $item->save();
                }
            }

            // Restore Item
            $item->restore();
        }

        // Return response
        return response('Done!', 204);
    }
}
