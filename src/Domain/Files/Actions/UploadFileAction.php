<?php
namespace Domain\Files\Actions;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Models\File as UserFile;
use Domain\Traffic\Actions\RecordUploadAction;
use App\Users\Actions\CheckStorageCapacityAction;

class UploadFileAction
{
    public function __construct(
        public RecordUploadAction $recordUpload,
        public CheckStorageCapacityAction $checkStorageCapacity,
        public CreateImageThumbnailAction $createImageThumbnail,
        public MoveFileToExternalStorageAction $moveFileToExternalStorage,
    ) {
    }

    /**
     * Upload new file
     */
    public function __invoke(
        UploadRequest $request,
        ?Share $shared = null,
    ) {
        // Get parent_id from request
        $file = $request->file('file');

        // dd($this->createFolderStructure($request->input('path'), $request->input('folder_id')));

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
        $limit = get_settings('upload_limit');

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
            ($this->checkStorageCapacity)($user_id, $file_size, $temp_filename);

            // Create thumbnail
            $thumbnail = ($this->createImageThumbnail)("chunks/$temp_filename", $disk_file_name, $user_id);

            // Move finished file from chunk to file-manager directory
            $disk_local->move("chunks/$temp_filename", "files/$user_id/$disk_file_name");

            // Move files to external storage
            if (! is_storage_driver(['local'])) {
                ($this->moveFileToExternalStorage)($disk_file_name, $user_id);
            }

            // Store user upload size
            ($this->recordUpload)($file_size, $user_id);

            // Return new file
            return UserFile::create([
                'mimetype'  => get_file_type_from_mimetype($file_mimetype),
                'type'      => get_file_type($file_mimetype),
                'folder_id' => $this->create_folder_structure($request->input('path'), $request->input('folder_id'), $user_id),
                'metadata'  => $metadata,
                'name'      => $request->input('filename'),
                'basename'  => $disk_file_name,
                'author'    => $shared ? 'visitor' : 'user',
                'thumbnail' => $thumbnail,
                'filesize'  => $file_size,
                'user_id'   => $user_id,
            ]);
        }
    }

    /**
     * Create structure fot the Folder upload
     */
    private function create_folder_structure ($path, $parent, $user_id) 
    {
        $folders = array_slice(explode('/', $path), 1, -1);

        $parent_id = $parent;

        $last_folder = $parent;

        $structure = Folder::whereIn('name', $folders)->with('parent')->get();

      
        if( count($folders) > 0) {

            if(count($folders) === count($structure) ) {

                $last_folder = $this->check_folder_structure($structure, $folders);
                
            } else if (count($folders) !== count($structure)) {


                if(count($structure) > 0) {

                    $data = $this->check_exist_folders($structure, $folders);

                    $folders = $data[0];

                    $parent_id = $data[1];

                }

                foreach($folders as $folder) {

                    $new_folder = Folder::create([
                                    'name' => $folder,
                                    'parent_id' => $parent_id,
                                    'user_id' => $user_id,
                                ]);
                                
                    $parent_id = $new_folder->id;
        
                    $last_folder = $new_folder->id;
                };
            }
        } 
        
        return $last_folder;
    }

    /**
     * Check if is the structure correct 
     */
    private function check_folder_structure($structure, $folders)
    {

        $parent_name = '';

        $validate = false;

        foreach(array_reverse($folders) as $folder) {
            
            $item = $structure->where('name', $folder);

            $parent = $item->pluck('parent')->pluck('name')[0];

            // Check if folder have valid parent name
            if( $parent && $folder === $parent_name || $parent_name == '') {

                $parent_name = $parent;

                $validate = true;

            } else if ($parent && $folder != $parent_name || $parent_name == ''){ 

                $validate = false;
            }
        }
       
        if($validate) {
            return $structure->where('name', $folders[array_key_last($folders)])->first()->id;
            
        } else {
            return false;
        }

    }

    /**
     * Return the folders that is need to create in already created structure and last created parent
     */
    private function check_exist_folders($structure, $folders)
    {

       $create_folders = [];
       $last_parent = '';

       foreach($folders as $folder) {

            // Filter folders that is need to create
           if(! $structure->where('name', $folder)->first()) {
               array_push($create_folders, $folder);
           }else {

                // Find last created folder
               $last_parent = $structure->where('name', $folder)->first()->id;
           }
       }

       return [$create_folders, $last_parent];
    }
}
