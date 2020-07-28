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

        // Create folder
        $folder = FileManagerFolder::create([
            'parent_id'  => $request->parent_id,
            'unique_id'  => get_unique_id(),
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
        if ($request->type === 'folder') {

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
            if ($request->force_delete) {

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
            if (!$request->force_delete) {

                // Remove folder from user favourites
                $user->favourite_folders()->detach($unique_id);

                // Soft delete folder record
                $folder->delete();
            }
        }

        // Delete item
        if ($request->type !== 'folder') {

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
            if ($request->force_delete) {

                // Delete file
                Storage::delete('/file-manager/' . $file->basename);

                // Delete thumbnail if exist
                if ($file->thumbnail) Storage::delete('/file-manager/' . $file->getRawOriginal('thumbnail'));

                // Delete file permanently
                $file->forceDelete();
            }

            // Soft delete file
            if (!$request->force_delete) {

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
        $folder_id = $request->parent_id === 0 ? 0 : $request->parent_id;
        $file = $request->file('file');

        // Check or create directories
        self::check_directories(['chunks', 'file-manager']);

        // Generate file
        File::append(storage_path() . '/app/chunks/' . $file->getClientOriginalName(), $file->get());

        // If last then process file
        if ($request->has('is_last') && $request->boolean('is_last')) {

            // Get original file name
            $original_file_name = basename('chunks/' . $file->getClientOriginalName(), '.part');

            // Rename chunk part to original file name in chunk directory
            Storage::disk('local')->move('chunks/' . $file->getClientOriginalName(), 'chunks/' . $original_file_name);

            // Get user data
            $user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
            $user_id = is_null($shared) ? Auth::id() : $shared->user_id;
            $user_storage_used = user_storage_percentage($user_id, Storage::disk('local')->size('chunks/' . $original_file_name));

            // Get storage limitation setup
            $storage_limitation = get_setting('storage_limitation');

            // Check if user can upload
            if ($storage_limitation && $user_storage_used >= 100) {

                // Delete file
                Storage::disk('local')->delete('chunks/' . $original_file_name);

                // Abort uploading
                abort(423, 'You exceed your storage limit!');
            }

            // File name
            $prefixed_file_name = Str::random() . '-' . str_replace(' ', '', $original_file_name);

            // Create thumbnail
            $thumbnail = self::get_image_thumbnail('chunks/' . $original_file_name, $prefixed_file_name, $file);

            // Store to disk
            Storage::disk('local')->move('chunks/' . $original_file_name, 'file-manager/' . $prefixed_file_name);

            // Store file
            $options = [
                'name'       => pathinfo($file->getClientOriginalName())['filename'],
                'mimetype'   => get_file_type_from_mimetype(Storage::disk('local')->mimeType('file-manager/' . $prefixed_file_name)),
                'unique_id'  => get_unique_id(),
                'user_scope' => $user_scope,
                'folder_id'  => $folder_id,
                'thumbnail'  => $thumbnail,
                'basename'   => $prefixed_file_name,
                'filesize'   => Storage::disk('local')->size('file-manager/' . $prefixed_file_name),
                'type'       => get_file_type('file-manager/' . $prefixed_file_name),
                'user_id'    => $user_id,
            ];

            // Move files to external storage
            if (! is_storage_driver(['local'])) {
                self::move_to_external_storage($prefixed_file_name, $thumbnail);
            }

            // Return new file
            return FileManagerFile::create($options);
        }
    }

    /**
     * Move file to external storage if is set
     *
     * @param string $filename
     * @param string|null $thumbnail
     */
    private static function move_to_external_storage(string $filename, ?string $thumbnail): void
    {
        foreach ([$filename, $thumbnail] as $file) {

            // Check if file exist
            if (!$file) continue;

            // Get file size
            $filesize = Storage::disk('local')->size('file-manager/' . $filename);

            // If file is bigger than 5.2MB then run multipart upload
            if ($filesize > 5242880) {

                // Get driver
                $driver = \Storage::getDriver();

                // Get adapter
                $adapter = $driver->getAdapter();

                // Get client
                $client = $adapter->getClient();

                // Prepare the upload parameters.
                $uploader = new MultipartUploader($client, storage_path() . '/app/file-manager/' . $file, [
                    'bucket' => $adapter->getBucket(),
                    'key'    => 'file-manager/' . $file
                ]);

                // Upload content
                try {
                    $uploader->upload();

                } catch (MultipartUploadException $e) {

                    Log::error($e->getMessage());

                    // Delete file after error
                    Storage::disk('local')->delete('file-manager/' . $file);

                    throw new HttpException(409, $e->getMessage());
                }

            } else {

                // Stream file object to s3
                Storage::putFileAs('/file-manager', storage_path() . '/app/file-manager/' . $file, $file, 'private');
            }

            // Delete file after upload
            Storage::disk('local')->delete('file-manager/' . $file);
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

            if (! is_storage_driver(['local'])) {
                if (!Storage::exists($directory)) {
                    Storage::makeDirectory($directory);
                }
            }
        }
    }

    /**
     * Create thumbnail for images
     *
     * @param string $chunk_file_path
     * @param string $filename
     * @param $file
     * @return string|null
     */
    private static function get_image_thumbnail(string $chunk_file_path, string $filename, $file)
    {
        if (in_array(Storage::disk('local')->mimeType($chunk_file_path), ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {

            // Get thumbnail name
            $thumbnail = 'thumbnail-' . $filename;

            // Create intervention image
            $image = Image::make(storage_path() . '/app/' . $chunk_file_path)->orientate();

            // Resize image
            $image->resize(564, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to disk
            Storage::disk('local')->put('file-manager/' . $thumbnail, $image);

        } elseif (Storage::disk('local')->mimeType($chunk_file_path) === 'image/svg+xml') {

            $thumbnail = $filename;
        }

        return $thumbnail ?? null;
    }
}