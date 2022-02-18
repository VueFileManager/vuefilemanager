<?php

namespace Domain\UploadRequest\Controllers;

use DB;
use Domain\Folders\Models\Folder;
use Domain\UploadRequest\Models\UploadRequest;
use Illuminate\Http\Request;

class UploadFilesForUploadRequestController
{
    public function __invoke(Request $request, UploadRequest $uploadRequest)
    {
        $folder = Folder::where('id', $uploadRequest->id);

        // Create folder if not exist
        if ($folder->doesntExist()) {

            $timestampName = format_date($uploadRequest->created_at, 'd. M. Y');

            DB::table('folders')->insert([
                'id'        => $uploadRequest->id,
                'parent_id' => $uploadRequest->folder_id,
                'user_id'   => $uploadRequest->user_id,
                'name'      => "Upload Request from $timestampName",
            ]);
        }

        return response('Done!', 201);
    }
}