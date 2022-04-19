<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;

class MoveFileToFTPStorageAction
{
    /**
     * Move file to external storage if is set
     */
    public function __invoke(
        string $file,
        string $userId
    ): void {
        // Stream file object to ftp
        Storage::putFileAs("files/$userId", config('filesystems.disks.local.root') . "/files/$userId/$file", $file, 'private');

        // Delete file after upload
        Storage::disk('local')->delete("files/$userId/$file");
    }
}
