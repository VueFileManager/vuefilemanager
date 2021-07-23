<?php
namespace App\Users\Actions;

use Illuminate\Support\Facades\Storage;

class CheckStorageCapacityAction
{
    /**
     * Check if user has enough space to upload file
     */
    public function __invoke(
        string $user_id,
        int $file_size,
        string $temp_filename,
    ): void {
        // Get user storage percentage and get storage_limitation setting
        $user_storage_used = user_storage_percentage($user_id, $file_size);

        // Check if user can upload
        if (get_settings('storage_limitation') && $user_storage_used >= 100) {
            // Delete file
            Storage::disk('local')
                ->delete("chunks/$temp_filename");

            // Abort uploading
            // TODO: test pre exceed storage limit
            abort(423, 'You exceed your storage limit!');
        }
    }
}
