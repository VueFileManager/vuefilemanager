<?php
namespace Domain\UploadRequest\Controllers;

use Illuminate\Support\Arr;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\UploadRequest\Models\UploadRequest;

class DeleteFileOrFolderController
{
    public function __invoke(
        DeleteItemRequest $request,
        UploadRequest $uploadRequest
    ): JsonResponse {
        foreach ($request->input('items') as $file) {
            // Get file or folder item
            $item = get_item($file['type'], $file['id']);

            // Delete folder
            if ($file['type'] === 'folder') {
                $this->destroyFolder($item);
            }

            // Delete file
            if ($file['type'] !== 'folder') {
                $this->destroyFile($item);
            }
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'Items was successfully deleted.',
        ]);
    }

    private function destroyFile(File $file): void
    {
        // Delete file
        Storage::delete("/files/$file->user_id/$file->basename");

        // Delete thumbnail if exist
        if ($file->type === 'image') {
            getThumbnailFileList($file->basename)
                ->each(fn ($thumbnail) => Storage::delete("files/$file->user_id/$thumbnail"));
        }

        // Delete file permanently
        $file->forceDelete();
    }

    private function destroyFolder(Folder $folder): void
    {
        // Get children files
        $files = File::whereIn('parent_id', Arr::flatten([filter_folders_ids($folder->folders), $folder->id]))
            ->get();

        // Remove all children files
        foreach ($files as $file) {
            // Delete file
            Storage::delete("/files/$file->user_id/$file->basename");

            // Delete thumbnail if exist
            if ($file->type === 'image') {
                getThumbnailFileList($file->basename)
                    ->each(fn ($thumbnail) => Storage::delete("files/$file->user_id/$thumbnail"));
            }

            // Delete file permanently
            $file->forceDelete();
        }

        $folder->forceDelete();
    }
}
