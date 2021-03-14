<?php

namespace App\Services;

use App;
use App\Models\Folder;
use App\Models\Share;
use App\Models\File as UserFile;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\Models\User;
use App\Models\Zip;
use Aws\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use League\Flysystem\FileNotFoundException;
use Madnest\Madzipper\Facades\Madzipper;
use Symfony\Component\HttpKernel\Exception\HttpException;


class FileManagerService
{
    private $helper;

    public function __construct()
    {
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Zip requested folder
     *
     * @param $id
     * @param $shared
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function zip_folder($id, $shared = null)
    {
        // Get folder
        $requested_folder = Folder::with(['folders.files', 'files'])
            ->where('id', $id)
            ->where('user_id', Auth::id() ?? $shared->user_id)
            ->with('folders')
            ->first();

        $files = get_files_for_zip($requested_folder, collect([]));

        // Local storage instance
        $disk_local = Storage::disk('local');

        // Move file to local storage from external storage service
        if (!is_storage_driver('local')) {
            $files->each(function ($file) use ($disk_local) {
                try {
                    $disk_local->put("temp/$file->basename", Storage::get("files/$file->user_id/$file->basename"));
                } catch (FileNotFoundException $e) {
                    throw new HttpException(404, 'File not found');
                }
            });
        }

        // Get zip path
        $zip_name = Str::random(16) . '-' . Str::slug($requested_folder->name) . '.zip';

        // Create zip
        $zip = Madzipper::make($disk_local->path("zip/$zip_name"));

        // Get files folder on local storage drive
        $directory = is_storage_driver('local') ? 'files' : 'temp';

        // Add files to zip
        foreach ($files as $file) {
            $zip
                ->folder($file['folder_path'])
                ->addString(
                    $file['name'],
                    File::get($disk_local->path("/$directory/$requested_folder->user_id/{$file['basename']}"))
                );
        }

        // Close zip
        $zip->close();

        // Delete temporary files
        if (!is_storage_driver('local')) {

            foreach ($files as $file) {
                $disk_local->delete('temp/' . $file['basename']);
            }
        }

        // Store zip record
        return Zip::create([
            'user_id'      => $shared->user_id ?? Auth::id(),
            'shared_token' => $shared->token ?? null,
            'basename'     => $zip_name,
        ]);
    }

    /**
     * Zip selected files, store it in /zip folder and retrieve zip record
     *
     * @param $files
     * @param null $shared
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function zip_files($files, $shared = null)
    {
        // Local storage instance
        $disk_local = Storage::disk('local');

        // Move file to local storage from external storage service
        if (!is_storage_driver('local')) {
            $files->each(function ($file) use ($disk_local) {
                try {
                    $disk_local->put("temp/$file->basename", Storage::get("files/$file->user_id/$file->basename"));
                } catch (FileNotFoundException $e) {
                    throw new HttpException(404, 'File not found');
                }
            });
        }

        // Get zip path
        $zip_name = Str::random(16) . '.zip';

        // Create zip
        $zip = Madzipper::make($disk_local->path("zip/$zip_name"));

        // Get files folder on local storage drive
        $directory = is_storage_driver('local') ? 'files' : 'temp';

        // Add files to zip
        $files->each(function ($file) use ($zip, $directory, $disk_local) {
            $zip->addString(
                "$file->name.$file->mimetype",
                File::get($disk_local->path("/$directory/$file->user_id/$file->basename")));
        });

        // Close zip
        $zip->close();

        // Delete temporary files
        if (!is_storage_driver('local')) {
            $files->each(function ($file) use ($disk_local) {
                $disk_local->delete("temp/$file->basename");
            });
        }

        // Store zip record
        return Zip::create([
            'user_id'      => $shared->user_id ?? Auth::id(),
            'shared_token' => $shared->token ?? null,
            'basename'     => $zip_name,
        ]);
    }

    /**
     * Create new directory
     *
     * @param $request
     * @param null $shared
     * @return Folder|\Illuminate\Database\Eloquent\Model
     */
    public function create_folder($request, $shared = null)
    {
        // Get variables
        //$user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
        $user_scope = is_null($shared) ? 'master' : 'editor';

        $name = $request->has('name') ? $request->input('name') : 'New Folder';
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        // Create folder
        $folder = Folder::create([
            'parent_id'  => $request->parent_id,
            'user_scope' => $user_scope,
            'user_id'    => $user_id,
            'type'       => 'folder',
            'name'       => $name,
            'icon_color' => isset($request->icon['color']) ? $request->icon['color'] : null,
            'icon_emoji' => isset($request->icon['emoji']) ? $request->icon['emoji'] : null,
        ]);

        // Return new folder
        return $folder;
    }

    /**
     * Rename item name
     *
     * @param RenameItemRequest $request
     * @param $id
     * @param null $shared
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function rename_item($request, $id, $shared = null)
    {
        // Get user id
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        // Get item
        $item = get_item($request->type, $id, $user_id);

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
     * @param $item
     * @param $id
     * @param null $shared
     * @throws \Exception
     */
    public function delete_item($item, $id, $shared = null)
    {
        // Delete folder
        if ($item['type'] === 'folder') {

            // Get folder
            $folder = Folder::withTrashed()
                ->with('folders')
                ->find($id);

            // Get folder shared record
            $shared = Share::where('type', 'folder')
                ->where('item_id', $id)
                ->first();

            // Delete folder shared record
            if ($shared) {
                $shared->delete();
            }

            // Remove folder from user favourites
            DB::table('favourite_folder')
                ->where('folder_id', $folder->id)
                ->delete();

            // Soft delete items
            if (!$item['force_delete']) {

                // Soft delete folder record
                $folder->delete();
            }

            // Force delete children files
            if ($item['force_delete']) {

                // Get children folder ids
                $child_folders = filter_folders_ids($folder->trashed_folders, 'id');

                // Get children files
                $files = UserFile::onlyTrashed()
                    ->whereIn('folder_id', Arr::flatten([$id, $child_folders]))
                    ->get();

                // Remove all children files
                foreach ($files as $file) {

                    // Delete file
                    Storage::delete("/files/$file->user_id/$file->basename");

                    // Delete thumbnail if exist
                    if ($file->thumbnail) {
                        Storage::delete(
                            "/files/$file->user_id/{$file->getRawOriginal('thumbnail')}"
                        );
                    }

                    // Delete file permanently
                    $file->forceDelete();
                }

                // Delete folder record
                $folder->forceDelete();
            }
        }

        // Delete item
        if ($item['type'] === 'file') {

            // Get file
            $file = UserFile::withTrashed()
                ->find($id);

            // Get folder shared record
            $shared = Share::where('type', 'file')
                ->where('item_id', $id)
                ->first();

            // Delete file shared record
            if ($shared) {
                $shared->delete();
            }

            // Force delete file
            if ($item['force_delete']) {

                // Delete file
                Storage::delete("/files/$file->user_id/$file->basename");

                // Delete thumbnail if exist
                if ($file->thumbnail) {
                    Storage::delete(
                        "/files/$file->user_id/{$file->getRawOriginal('thumbnail')}"
                    );
                }

                // Delete file permanently
                $file->forceDelete();
            }

            // Soft delete file
            if (!$item['force_delete']) {

                // Soft delete file
                $file->delete();
            }
        }
    }

    /**
     * Move folder or file to new location
     *
     * @param $request
     * @param $to_id
     */
    public function move($request, $to_id)
    {
        foreach ($request->items as $item) {

            // Move folder
            if ($item['type'] === 'folder') {

                Folder::find($item['id'])
                    ->update([
                        'parent_id' => $to_id
                    ]);
            }

            // Move file
            if ($item['type'] === 'file') {
                UserFile::find($item['id'])
                    ->update([
                        'folder_id' => $to_id
                    ]);
            }
        }
    }

    /**
     * Upload file
     *
     * @param $request
     * @param null $shared
     * @return File|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function upload($request, $shared = null)
    {
        // Get parent_id from request
        $file = $request->file('file');

        // File name
        $user_file_name = basename('chunks/' . substr($file->getClientOriginalName(), 17), '.part');
        $disk_file_name = basename('chunks/' . $file->getClientOriginalName(), '.part');
        $temp_filename = $file->getClientOriginalName();

        // File Path
        $file_path = Storage::disk('local')->path('chunks/' . $temp_filename);

        // Generate file
        File::append($file_path, $file->get());

        // Size of file
        $file_size = File::size($file_path);

        // Size of limit
        $limit = get_setting('upload_limit');

        // File size handling
        if ($limit && $file_size > format_bytes($limit)) abort(413);

        // If last then process file
        if ($request->boolean('is_last')) {

            $metadata = get_image_meta_data($file);

            $disk_local = Storage::disk('local');

            // Get user data
            //$user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
            $user_scope = is_null($shared) ? 'master' : 'editor';
            $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

            // File Info
            $file_size = $disk_local->size('chunks/' . $temp_filename);

            $file_mimetype = $disk_local->mimeType('chunks/' . $temp_filename);

            // Check if user has enough space to upload file
            $this->helper->check_user_storage_capacity($user_id, $file_size, $temp_filename);

            // Create thumbnail
            $thumbnail = self::get_image_thumbnail('chunks/' . $temp_filename, $disk_file_name, $user_id);

            // Move finished file from chunk to file-manager directory
            $disk_local->move('chunks/' . $temp_filename, "files/$user_id/$disk_file_name");

            // Move files to external storage
            if (!is_storage_driver(['local'])) {

                // Move file to external storage service
                $this->helper->move_to_external_storage($disk_file_name, $thumbnail);
            }

            // Store user upload size
            User::find($user_id)
                ->record_upload($file_size);

            // Return new file
            return UserFile::create([
                'mimetype'   => get_file_type_from_mimetype($file_mimetype),
                'type'       => get_file_type($file_mimetype),
                'folder_id'  => $request->folder_id,
                'metadata'   => $metadata,
                'name'       => $user_file_name,
                'basename'   => $disk_file_name,
                'user_scope' => $user_scope,
                'thumbnail'  => $thumbnail,
                'filesize'   => $file_size,
                'user_id'    => $user_id,
            ]);
        }
    }

    /**
     * Store folder icon
     *
     * @param $request
     * @param $id
     */
    public function set_folder_icon($request, $id)
    {
        // Get folder
        $folder = Folder::find($id);

        // Set default folder icon
        if ($request->emoji === 'default') {
            $folder->update([
                'emoji' => null,
                'color' => null,
            ]);
        }

        // Set emoji
        if ($request->filled('emoji')) {
            $folder->update([
                'emoji' => $request->emoji,
                'color' => null,
            ]);
        }

        // Set color
        if ($request->filled('color')) {
            $folder->update([
                'emoji' => null,
                'color' => $request->color,
            ]);
        }
    }

    /**
     * Create thumbnail for images
     *
     * @param string $file_path
     * @param string $filename
     * @param string $user_id
     * @param $file
     * @return string|null
     */
    private function get_image_thumbnail(string $file_path, string $filename, string $user_id)
    {
        $local_disk = Storage::disk('local');

        // Create thumbnail from image
        if (in_array($local_disk->mimeType($file_path), ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {

            // Get thumbnail name
            $thumbnail = 'thumbnail-' . $filename;

            // Create intervention image
            $image = Image::make($local_disk->path($file_path))->orientate();

            // Resize image
            $image->resize(512, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to disk
            $local_disk->put("files/$user_id/$thumbnail", $image);
        }

        // Return thumbnail as svg file
        if ($local_disk->mimeType($file_path) === 'image/svg+xml') {

            $thumbnail = $filename;
        }

        return $thumbnail ?? null;
    }
}