<?php

namespace App\Http\Tools;

use App;
use App\Share;
use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\User;
use Aws\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Editor
{
    /**
     * Create new directory
     *
     * @param $request
     * @param null $shared
     * @return FileManagerFolder|\Illuminate\Database\Eloquent\Model
     */
    public static function create_folder($request, $shared = null)
    {
        // Get variables
        $user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
        $name = $request->has('name') ? $request->input('name') : 'New Folder';
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;
        $unique_id = get_unique_id();

        // Create folder
        $folder = FileManagerFolder::create([
            'parent_id'  => $request->parent_id,
            'unique_id'  => $unique_id,
            'user_scope' => $user_scope,
            'user_id'    => $user_id,
            'type'       => 'folder',
            'name'       => $name,
        ]);

        // Return new folder
        return $folder;
    }

    /**
     * Rename item name
     *
     * @param RenameItemRequest $request
     * @param $unique_id
     * @param null $shared
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function rename_item($request, $unique_id, $shared = null)
    {
        // Get user id
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        // Get item
        $item = get_item($request->type, $unique_id, $user_id);

        // Rename item
        $item->update([
            'name' => $request->name
        ]);

        // Return updated item
        return $item;
    }

    /**
     * Delete file or folder
     *
     * @param $request
     * @param $unique_id
     * @param null $shared
     * @throws \Exception
     */
    public static function delete_item($request, $unique_id, $shared = null)
    {
        // Get user id
        $user = is_null($shared) ? Auth::user() : User::findOrFail($shared->user_id);

        // Delete folder
        if ($request->input('data.type') === 'folder') {

            // Get folder
            $folder = FileManagerFolder::withTrashed()
                ->with(['folders'])
                ->where('user_id', $user->id)
                ->where('unique_id', $unique_id)
                ->first();

            // Get folder shared record
            $shared = Share::where('user_id', $user->id)
                ->where('type', '=', 'folder')
                ->where('item_id', $unique_id)
                ->first();

            // Delete folder shared record
            if ($shared) {
                $shared->delete();
            }

            // Force delete children files
            if ($request->input('data.force_delete')) {

                // Get children folder ids
                $child_folders = filter_folders_ids($folder->trashed_folders, 'unique_id');

                // Get children files
                $files = FileManagerFile::onlyTrashed()
                    ->where('user_id', $user->id)
                    ->whereIn('folder_id', Arr::flatten([$unique_id, $child_folders]))
                    ->get();

                // Remove all children files
                foreach ($files as $file) {

                    // Delete file
                    Storage::delete('/file-manager/' . $file->basename);

                    // Delete thumbnail if exist
                    if (!is_null($file->thumbnail)) Storage::delete('/file-manager/' . $file->getRawOriginal('thumbnail'));

                    // Delete file permanently
                    $file->forceDelete();
                }

                // Delete folder record
                $folder->forceDelete();
            }

            // Soft delete items
            if (!$request->input('data.force_delete')) {

                // Remove folder from user favourites
                $user->favourite_folders()->detach($unique_id);

                // Soft delete folder record
                $folder->delete();
            }
        }

        // Delete item
        if ($request->input('data.type') !== 'folder') {

            // Get file
            $file = FileManagerFile::withTrashed()
                ->where('user_id', $user->id)
                ->where('unique_id', $unique_id)
                ->first();

            // Get folder shared record
            $shared = Share::where('user_id', $user->id)
                ->where('type', '=', 'file')
                ->where('item_id', $unique_id)
                ->first();

            // Delete file shared record
            if ($shared) {
                $shared->delete();
            }

            // Force delete file
            if ($request->input('data.force_delete')) {

                // Delete file
                Storage::delete('/file-manager/' . $file->basename);

                // Delete thumbnail if exist
                if ($file->thumbnail) Storage::delete('/file-manager/' . $file->getRawOriginal('thumbnail'));

                // Delete file permanently
                $file->forceDelete();
            }

            // Soft delete file
            if (!$request->input('data.force_delete')) {

                // Soft delete file
                $file->delete();
            }
        }
    }

    /**
     * Move folder or file to new location
     *
     * @param $request
     * @param $unique_id
     * @param null $shared
     */
    public static function move($request, $unique_id, $shared = null)
    {
        // Get user id
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        if ($request->from_type === 'folder') {

            // Move folder
            $item = FileManagerFolder::where('user_id', $user_id)
                ->where('unique_id', $unique_id)
                ->firstOrFail();

            $item->update([
                'parent_id' => $request->to_unique_id
            ]);

        } else {

            // Move file under new folder
            $item = FileManagerFile::where('user_id', $user_id)
                ->where('unique_id', $unique_id)
                ->firstOrFail();

            $item->update([
                'folder_id' => $request->to_unique_id
            ]);
        }
    }

    /**
     * Upload file
     *
     * @param $request
     * @param null $shared
     * @return FileManagerFile|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function upload($request, $shared = null)
    {
        // Get parent_id from request
        $file = $request->file('file');

        // Check or create directories
        self::check_directories(['chunks', 'file-manager']);

        // File name
        $user_file_name = basename('chunks/' . substr($file->getClientOriginalName(), 17), '.part');
        $disk_file_name = basename('chunks/' . $file->getClientOriginalName(), '.part');
        $temp_filename = $file->getClientOriginalName();

        // Generate file
        File::append(config('filesystems.disks.local.root') . '/chunks/' . $temp_filename, $file->get());

        // If last then process file
        if ($request->boolean('is_last')) {

            $metadata = get_image_meta_data($file);

            $disk_local = Storage::disk('local');
            $unique_id = get_unique_id();

            // Get user data
            $user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
            $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

            // File Info
            $file_size = $disk_local->size('chunks/' . $temp_filename);
            $file_mimetype = $disk_local->mimeType('chunks/' . $temp_filename);

            // Check if user has enough space to upload file
            self::check_user_storage_capacity($user_id, $file_size, $temp_filename);

            // Create thumbnail
            $thumbnail = self::get_image_thumbnail('chunks/' . $temp_filename, $disk_file_name);

            // Move finished file from chunk to file-manager directory
            $disk_local->move('chunks/' . $temp_filename, 'file-manager/' . $disk_file_name);

            // Move files to external storage
            if (!is_storage_driver(['local'])) {

                // Clear failed uploads if exists
                self::clear_failed_files();

                // Move file to external storage service
                self::move_to_external_storage($disk_file_name, $thumbnail);
            }

            // Store file
            $options = [
                'mimetype'   => get_file_type_from_mimetype($file_mimetype),
                'type'       => get_file_type($file_mimetype),
                'folder_id'  => $request->parent_id,
                'metadata'   => $metadata,
                'name'       => $user_file_name,
                'unique_id'  => $unique_id,
                'basename'   => $disk_file_name,
                'user_scope' => $user_scope,
                'thumbnail'  => $thumbnail,
                'filesize'   => $file_size,
                'user_id'    => $user_id,
            ];

            // Return new file
            return FileManagerFile::create($options);
        }
    }

    /**
     * Clear failed files
     */
    private static function clear_failed_files()
    {
        $local_disk = Storage::disk('local');

        // Get all files from storage
        $files = collect([
            $local_disk->allFiles('file-manager'),
            $local_disk->allFiles('chunks')
        ])->collapse();

        $files->each(function ($file) use ($local_disk) {

            // Get the file's last modification time.
            $last_modified = $local_disk->lastModified($file);

            // Get diffInHours
            $diff = Carbon::parse($last_modified)->diffInHours(Carbon::now());

            // Delete if file is in local storage more than 24 hours
            if ($diff > 24) {

                Log::info('Failed file or chunk ' . $file . ' deleted.');

                // Delete file from local storage
                $local_disk->delete($file);
            }
        });
    }

    /**
     * Move file to external storage if is set
     *
     * @param string $filename
     * @param string|null $thumbnail
     */
    private static function move_to_external_storage(string $filename, ?string $thumbnail): void
    {
        $disk_local = Storage::disk('local');

        foreach ([$filename, $thumbnail] as $file) {

            // Check if file exist
            if (!$file) continue;

            // Get file size
            $filesize = $disk_local->size('file-manager/' . $file);

            // If file is bigger than 5.2MB then run multipart upload
            if ($filesize > 5242880) {

                // Get driver
                $driver = \Storage::getDriver();

                // Get adapter
                $adapter = $driver->getAdapter();

                // Get client
                $client = $adapter->getClient();

                // Prepare the upload parameters.
                $uploader = new MultipartUploader($client, config('filesystems.disks.local.root') . '/file-manager/' . $file, [
                    'bucket' => $adapter->getBucket(),
                    'key'    => 'file-manager/' . $file
                ]);

                try {

                    // Upload content
                    $uploader->upload();

                } catch (MultipartUploadException $e) {

                    // Write error log
                    Log::error($e->getMessage());

                    // Delete file after error
                    $disk_local->delete('file-manager/' . $file);

                    throw new HttpException(409, $e->getMessage());
                }

            } else {

                // Stream file object to s3
                Storage::putFileAs('file-manager', config('filesystems.disks.local.root') . '/file-manager/' . $file, $file, 'private');
            }

            // Delete file after upload
            $disk_local->delete('file-manager/' . $file);
        }
    }

    /**
     * Check if directories 'chunks' and 'file-manager exist', if no, then create
     *
     * @param $directories
     */
    private static function check_directories($directories): void
    {
        foreach ($directories as $directory) {

            if (!Storage::disk('local')->exists($directory)) {
                Storage::disk('local')->makeDirectory($directory);
            }

            if (!is_storage_driver(['local'])) {
                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }
            }
        }
    }

    /**
     * Create thumbnail for images
     *
     * @param string $file_path
     * @param string $filename
     * @param $file
     * @return string|null
     */
    private static function get_image_thumbnail(string $file_path, string $filename)
    {
        $local_disk = Storage::disk('local');

        // Create thumbnail from image
        if (in_array($local_disk->mimeType($file_path), ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {

            // Get thumbnail name
            $thumbnail = 'thumbnail-' . $filename;

            // Create intervention image
            $image = Image::make(config('filesystems.disks.local.root') . '/' . $file_path)->orientate();

            // Resize image
            $image->resize(512, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to disk
            $local_disk->put('file-manager/' . $thumbnail, $image);
        }

        // Return thumbnail as svg file
        if ($local_disk->mimeType($file_path) === 'image/svg+xml') {

            $thumbnail = $filename;
        }

        return $thumbnail ?? null;
    }

    /**
     * Check if user has enough space to upload file
     *
     * @param $user_id
     * @param int $file_size
     * @param $temp_filename
     */
    private static function check_user_storage_capacity($user_id, int $file_size, $temp_filename): void
    {
        // Get user storage percentage and get storage_limitation setting
        $user_storage_used = user_storage_percentage($user_id, $file_size);
        $storage_limitation = get_setting('storage_limitation');

        // Check if user can upload
        if ($storage_limitation && $user_storage_used >= 100) {

            // Delete file
            Storage::disk('local')->delete('chunks/' . $temp_filename);

            // Abort uploading
            abort(423, 'You exceed your storage limit!');
        }
    }
}