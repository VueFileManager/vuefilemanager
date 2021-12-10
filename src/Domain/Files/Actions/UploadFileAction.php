<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Str;
use Domain\Sharing\Models\Share;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Models\File as UserFile;
use Domain\Traffic\Actions\RecordUploadAction;
use App\Users\Actions\CheckStorageCapacityAction;

class UploadFileAction
{
    public function __construct(
        public RecordUploadAction $recordUpload,
        public CheckStorageCapacityAction $checkStorageCapacity,
        public CreateImageThumbnailAction $createImageThumbnail,
        public MoveFileToExternalStorageAction $moveFileToExternalStorage,
    ) {
    }

    /**
     * Upload new file
     */
    public function __invoke(
        UploadRequest $request,
        ?Share $shared = null,
    ) {
        $file = $request->file('file');

        $chunkName = $file->getClientOriginalName();

        // File name
        $fileName = Str::uuid() . '.' . $file->extension();

        // File Path
        $filePath = Storage::disk('local')->path('chunks/' . $chunkName);

        // Generate file
        File::append($filePath, $file->get());

        // Size of file
        $fileSize = File::size($filePath);

        // Size of limit
        $uploadLimit = get_settings('upload_limit');

        // File size handling
        if ($uploadLimit && $fileSize > format_bytes($uploadLimit)) {
            abort(413);
        }

        // If last then process file
        if ($request->boolean('is_last')) {
            $metadata = get_image_meta_data($file);

            $disk_local = Storage::disk('local');

            // Get user data
            $user_id = $shared->user_id ?? Auth::id();

            // File Info
            $fileSize = $disk_local->size("chunks/$chunkName");

            $file_mimetype = $disk_local->mimeType("chunks/$chunkName");

            // Check if user has enough space to upload file
            ($this->checkStorageCapacity)($user_id, $fileSize, $chunkName);

            // Create multiple image thumbnails
            ($this->createImageThumbnail)($fileName, $file, $user_id);

            // Move finished file from chunk to file-manager directory
            $disk_local->move("chunks/$chunkName", "files/$user_id/$fileName");

            // Move files to external storage
            if (! is_storage_driver('local')) {
                ($this->moveFileToExternalStorage)($fileName, $user_id);
            }

            // Store user upload size
            ($this->recordUpload)($fileSize, $user_id);

            // Return new file
            return UserFile::create([
                'mimetype'  => get_file_type_from_mimetype($file_mimetype),
                'type'      => get_file_type($file_mimetype),
                'parent_id' => $request->input('parent_id'),
                'metadata'  => $metadata,
                'name'      => $request->input('filename'),
                'basename'  => $fileName,
                'author'    => $shared ? 'visitor' : 'user',
                'filesize'  => $fileSize,
                'user_id'   => $user_id,
            ]);
        }
    }
}
