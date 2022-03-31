<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Str;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Models\File as UserFile;
use Domain\Traffic\Actions\RecordUploadAction;
use App\Users\Exceptions\InvalidUserActionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UploadFileAction
{
    public function __construct(
        public RecordUploadAction $recordUpload,
        public ProcessImageThumbnailAction $createImageThumbnail,
        public GetFileParentId $getFileParentId,
        public MoveFileToExternalStorageAction $moveFileToExternalStorage,
        public StoreFileExifMetadataAction $storeExifMetadata,
    ) {
    }

    /**
     * Upload new file
     *
     * @throws InvalidUserActionException|FileNotFoundException
     */
    public function __invoke(
        UploadRequest $request,
        ?string $userId = null,
    ) {
        $file = $request->file('file');

        $chunkName = $file->getClientOriginalName();

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
            $disk_local = Storage::disk('local');

            // File name
            $fileName = Str::uuid() . '.' . $request->input('extension');

            // Get user
            $user = $request->filled('parent_id')
                ? Folder::find($request->input('parent_id'))->getLatestParent()->user
                : auth()->user();

            // File Info
            $fileSize = $disk_local->size("chunks/$chunkName");
            $fileMimetype = $disk_local->mimeType("chunks/$chunkName");

            // Check if user has enough space to upload file
            if (! $user->canUpload($fileSize)) {
                Storage::disk('local')->delete("chunks/$chunkName");

                throw new InvalidUserActionException();
            }

            // Move finished file from chunk to file-manager directory
            $disk_local->move("chunks/$chunkName", "files/$user->id/$fileName");

            // Create multiple image thumbnails
            ($this->createImageThumbnail)($fileName, $file, $user->id);

            // Move files to external storage
            if (! isStorageDriver('local')) {
                ($this->moveFileToExternalStorage)($fileName, $user->id);
            }

            // Create new file
            $item = UserFile::create([
                'mimetype'   => $request->input('extension'),
                'type'       => get_file_type($fileMimetype),
                'parent_id'  => ($this->getFileParentId)($request, $user->id),
                'name'       => $request->input('filename'),
                'basename'   => $fileName,
                'filesize'   => $fileSize,
                'user_id'    => $user->id,
                'creator_id' => auth()->id(),
            ]);

            // Store exif metadata for files
            ($this->storeExifMetadata)($item, $file);

            // Return new file
            return $item;
        }
    }
}
