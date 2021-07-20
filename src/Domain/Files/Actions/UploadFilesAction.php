<?php


namespace Domain\Files\Actions;


use App\Users\Models\User;
use Domain\Files\Models\File as UserFile;
use Domain\Files\Requests\UploadRequest;
use Domain\Sharing\Models\Share;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Support\Services\HelperService;

class UploadFilesAction
{
    public function __construct(
        public HelperService $helper,
    ) {}

    /**
     * Upload new file
     */
    public function __invoke(
        UploadRequest $request,
        ?Share $shared = null,
    ): UserFile {
        // Get parent_id from request
        $file = $request->file('file');

        // File name
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
                ->recordUpload($file_size);

            // Return new file
            return UserFile::create([
                'mimetype'  => get_file_type_from_mimetype($file_mimetype),
                'type'      => get_file_type($file_mimetype),
                'folder_id' => $request->folder_id,
                'metadata'  => $metadata,
                'name'      => $request->filename,
                'basename'  => $disk_file_name,
                'author'    => $shared ? 'visitor' : 'user',
                'thumbnail' => $thumbnail,
                'filesize'  => $file_size,
                'user_id'   => $user_id,
            ]);
        }
    }
}