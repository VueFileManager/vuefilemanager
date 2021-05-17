<?php
namespace App\Services;

use DB;
use App\Models\Zip;
use App\Models\User;
use App\Models\Share;
use App\Models\Folder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\File as UserFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use App\Http\Requests\FileFunctions\RenameItemRequest;
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
        if (! is_storage_driver('local')) {
            foreach ($files as $file) {
                try {
                    $disk_local->put("temp/{$file['basename']}", Storage::get("files/$requested_folder->user_id/{$file['basename']}"));
                } catch (FileNotFoundException $e) {
                    throw new HttpException(404, 'File not found');
                }
            }
        }

        // Get zip path
        $zip_name = Str::random(16) . '-' . Str::slug($requested_folder->name) . '.zip';

        // Create zip
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zip = $zipper->make($disk_local->path("zip/$zip_name"));

        // Add files to zip
        foreach ($files as $file) {
            $file_path = is_storage_driver('local')
                ? $disk_local->path("files/$requested_folder->user_id/{$file['basename']}")
                : $disk_local->path("temp/{$file['basename']}");

            $zip
                ->folder($file['folder_path'])
                ->addString("{$file['name']}.{$file['mimetype']}", File::get($file_path));
        }

        // Close zip
        //$zip->close();

        // Delete temporary files
        if (! is_storage_driver('local')) {
            foreach ($files as $file) {
                $disk_local->delete('temp/' . $file['basename']);
            }
        }

        // Store zip record
        return Zip::create([
            'user_id' => $shared->user_id ?? Auth::id(),
            'shared_token' => $shared->token ?? null,
            'basename' => $zip_name,
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
        if (! is_storage_driver('local')) {
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
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zip = $zipper->make($disk_local->path("zip/$zip_name"));

        // Add files to zip
        $files->each(function ($file) use ($zip, $disk_local) {
            $file_path = is_storage_driver('local')
                ? $disk_local->path("files/$file->user_id/$file->basename")
                : $disk_local->path("temp/$file->basename");

            $zip->addString("$file->name.$file->mimetype", File::get($file_path));
        });

        // Close zip
        //$zip->close();

        // Delete temporary files
        if (! is_storage_driver('local')) {
            $files->each(function ($file) use ($disk_local) {
                $disk_local->delete("temp/$file->basename");
            });
        }

        // Store zip record
        return Zip::create([
            'user_id' => $shared->user_id ?? Auth::id(),
            'shared_token' => $shared->token ?? null,
            'basename' => $zip_name,
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
        return Folder::create([
            'parent_id' => $request->parent_id,
            'author' => $shared ? 'visitor' : 'user',
            'user_id' => $shared ? $shared->user_id : Auth::id(),
            'name' => $request->name,
            'color' => $request->color ?? null,
            'emoji' => $request->emoji ?? null,
        ]);
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
        $user_id = $shared ? $shared->user_id : Auth::id();

        // Get item
        $item = get_item($request->type, $id, $user_id);

        // Rename item
        $item->update([
            'name' => $request->name,
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
            if (! $item['force_delete']) {
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
        if ($item['type'] !== 'folder') {
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
            if (! $item['force_delete']) {
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
                    ->update(['parent_id' => $to_id]);
            }

            // Move file
            if ($item['type'] !== 'folder') {
                UserFile::find($item['id'])
                    ->update(['folder_id' => $to_id]);
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
        if ($limit && $file_size > format_bytes($limit)) {
            abort(413);
        }

        // If last then process file
        if ($request->boolean('is_last')) {
            $metadata = get_image_meta_data($file);

            $disk_local = Storage::disk('local');

            // Get user data
            $user_id = $shared->user_id ?? Auth::id();

            // File Info
            $file_size = $disk_local->size("chunks/$temp_filename");

            $file_mimetype = $disk_local->mimeType("chunks/$temp_filename");

            // Check if user has enough space to upload file
            $this->helper->check_user_storage_capacity($user_id, $file_size, $temp_filename);

            // Create thumbnail
            $thumbnail = $this->helper->create_image_thumbnail("chunks/$temp_filename", $disk_file_name, $user_id);

            // Move finished file from chunk to file-manager directory
            $disk_local->move("chunks/$temp_filename", "files/$user_id/$disk_file_name");

            // Move files to external storage
            if (! is_storage_driver(['local'])) {
                $this->helper->move_file_to_external_storage($disk_file_name, $user_id);
            }

            // Store user upload size
            User::find($user_id)
                ->record_upload($file_size);

            // Return new file
            return UserFile::create([
                'mimetype' => get_file_type_from_mimetype($file_mimetype),
                'type' => get_file_type($file_mimetype),
                'folder_id' => $request->folder_id,
                'metadata' => $metadata,
                'name' => $user_file_name,
                'basename' => $disk_file_name,
                'author' => $shared ? 'visitor' : 'user',
                'thumbnail' => $thumbnail,
                'filesize' => $file_size,
                'user_id' => $user_id,
            ]);
        }
    }

    /**
     * Store folder icon
     *
     * @param $request
     * @param $id
     */
    public function edit_folder_properties($request, $id)
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
}
