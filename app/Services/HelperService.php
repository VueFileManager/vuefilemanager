<?php
namespace App\Services;

use DB;
use App\Models\File;
use App\Models\Share;
use App\Models\Folder;
use Illuminate\Support\Arr;
use Aws\S3\MultipartUploader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Aws\Exception\MultipartUploadException;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HelperService
{
    /**
     * Delete all user data including files, folders, avatar etc.
     *
     * @param $user
     */
    public function erase_user_data($user)
    {
        // Delete user avatar if exists
        if ($user->settings->getRawOriginal('avatar')) {
            Storage::delete($user->settings->getRawOriginal('avatar'));
        }

        // Delete all user files
        Storage::deleteDirectory("files/$user->id");

        // Delete all user records in database
        collect(['folders', 'files', 'user_settings', 'shares', 'favourite_folder', 'zips'])
            ->each(function ($table) use ($user) {
                DB::table($table)
                    ->whereUserId($user->id)
                    ->delete();
            });
    }

    /**
     * Check access to requested directory
     *
     * @param int|array $requested_id
     * @param string $shared Shared record detail
     */
    public function check_item_access($requested_id, $shared)
    {
        // Get all children folders
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get all authorized parent folders by shared folder as root of tree
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Check user access
        if (is_array($requested_id)) {
            foreach ($requested_id as $id) {
                if (! in_array($id, $accessible_folder_ids)) {
                    abort(403);
                }
            }
        }

        if (! is_array($requested_id)) {
            if (! in_array($requested_id, $accessible_folder_ids)) {
                abort(403);
            }
        }
    }

    /**
     * Check user file access
     *
     * @param $shared
     * @param $file
     */
    public function check_guest_access_to_shared_items($shared, $file): void
    {
        // Check by parent folder permission
        if ($shared->type === 'folder') {
            $this->check_item_access($file->folder_id, $shared);
        }

        // Check by single file permission
        if ($shared->type === 'file') {
            if ($shared->item_id !== $file->id) {
                abort(403);
            }
        }
    }

    /**
     * Check if user has enough space to upload file
     *
     * @param $user_id
     * @param int $file_size
     * @param $temp_filename
     */
    public function check_user_storage_capacity($user_id, int $file_size, $temp_filename): void
    {
        // Get user storage percentage and get storage_limitation setting
        $user_storage_used = user_storage_percentage($user_id, $file_size);

        // Check if user can upload
        if (get_setting('storage_limitation') && $user_storage_used >= 100) {
            // Delete file
            Storage::disk('local')
                ->delete("chunks/$temp_filename");

            // Abort uploading
            // TODO: test pre exceed storage limit
            abort(423, 'You exceed your storage limit!');
        }
    }

    /**
     * Move file to external storage if is set
     *
     * @param string $file
     * @param string $user_id
     */
    public function move_file_to_external_storage($file, $user_id): void
    {
        $disk_local = Storage::disk('local');

        // Get file size
        $filesize = $disk_local->size("files/$user_id/$file");

        // If file is bigger than 5.2MB then run multipart upload
        if ($filesize > 5242880) {
            // Get driver
            $driver = \Storage::getDriver();

            // Get adapter
            $adapter = $driver->getAdapter();

            // Get client
            $client = $adapter->getClient();

            // Prepare the upload parameters.
            // TODO: replace local files with temp folder
            $uploader = new MultipartUploader($client, config('filesystems.disks.local.root') . "/files/$user_id/$file", [
                'bucket' => $adapter->getBucket(),
                'key' => "/files/$user_id/$file",
            ]);

            try {
                // Upload content
                $uploader->upload();
            } catch (MultipartUploadException $e) {
                // Write error log
                Log::error($e->getMessage());

                // Delete file after error
                $disk_local->delete("/files/$user_id/$file");

                throw new HttpException(409, $e->getMessage());
            }
        } else {
            // Stream file object to s3
            // TODO: replace local files with temp folder
            Storage::putFileAs("files/$user_id", config('filesystems.disks.local.root') . "/files/$user_id/$file", $file, 'private');
        }

        // Delete file after upload
        $disk_local->delete("/files/$user_id/$file");
    }

    /**
     * Create image thumbnail from gif, jpeg, jpg, png, webp or svg
     *
     * @param string $file_path
     * @param string $filename
     * @param string $user_id
     * @return string|null
     */
    public function create_image_thumbnail($file_path, $filename, $user_id)
    {
        // Create thumbnail from image
        if (in_array(Storage::disk('local')->mimeType($file_path), ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
            // Get thumbnail name
            $thumbnail = "thumbnail-$filename";

            // Create intervention image
            $image = Image::make(Storage::disk('local')->path($file_path))
                ->orientate();

            // Resize image
            $image->resize(512, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to disk
            Storage::put("files/$user_id/$thumbnail", $image);
        }

        // Return thumbnail as svg file
        if (Storage::disk('local')->mimeType($file_path) === 'image/svg+xml') {
            $thumbnail = $filename;
        }

        return $thumbnail ?? null;
    }

    /**
     * Call and download file
     *
     * @param $file
     * @param $user_id
     * @return mixed
     */
    public function download_file($file, $user_id)
    {
        // Get file path
        $path = "files/$user_id/$file->basename";

        // Check if file exist
        if (! Storage::exists($path)) {
            abort(404);
        }

        // Get pretty name
        $pretty_name = get_pretty_name($file->basename, $file->name, $file->mimetype);

        return response()
            ->download(Storage::path($path), $pretty_name, [
                'Accept-Ranges' => 'bytes',
                'Content-Type' => Storage::mimeType($path),
                'Content-Length' => Storage::size($path),
                'Content-Range' => 'bytes 0-600/' . Storage::size($path),
                'Content-Disposition' => "attachment; filename=$pretty_name",
            ]);
    }

    /**
     * Get image thumbnail for browser
     *
     * @param $file
     * @param $user_id
     * @return mixed
     */
    public function download_thumbnail_file($file, $user_id)
    {
        // Get file path
        $path = "/files/$user_id/{$file->getRawOriginal('thumbnail')}";

        // Check if file exist
        if (! Storage::exists($path)) {
            abort(404);
        }

        // Return image thumbnail
        return Storage::download($path, $file->getRawOriginal('thumbnail'));
    }

    /**
     * Get all folders and files under the share record
     *
     * @param $id
     * @param $shared
     * @return array
     */
    public function get_items_under_shared_by_folder_id($id, $shared): array
    {
        $folders = Folder::where('user_id', $shared->user_id)
            ->where('parent_id', $id)
            ->sortable()
            ->get();

        $files = File::where('user_id', $shared->user_id)
            ->where('folder_id', $id)
            ->sortable()
            ->get();

        return [$folders, $files];
    }

    /**
     * @param Share $shared
     */
    public function check_protected_share_record(Share $shared): void
    {
        if ($shared->is_protected) {
            $abort_message = "Sorry, you don't have permission";

            if (! request()->hasCookie('share_session')) {
                abort(403, $abort_message);
            }

            // Get shared session
            $share_session = json_decode(
                request()->cookie('share_session')
            );

            // Check if is requested same share record
            if ($share_session->token !== $shared->token) {
                abort(403, $abort_message);
            }

            // Check if share record was authenticated previously via ShareController@authenticate
            if (! $share_session->authenticated) {
                abort(403, $abort_message);
            }
        }
    }
}
