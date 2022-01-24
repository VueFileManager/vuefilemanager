<?php

namespace  Domain\Files\Actions;

use Domain\Folders\Models\Folder;


class CreateFolderStructureAction
{
    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function __invoke($path, $parent, $user_id)
    {
         
        $folders = array_slice(explode('/', $path), 1, -1);

        $parent_id = $parent;

        $last_folder = $parent;

        // Get already created structure of the file parents
        $structure = Folder::whereIn('name', $folders)->with('parent')->get();

        // If file have some parent folders
        if( count($folders) > 0) {

            // If uploading structure has same lenght as a already existed structure
            if( count($folders) === count($structure) ) {

                // Get correct file parent from the already craeted structure
                $last_folder = $this->get_file_parent($structure, $folders);
            
            } else if ( count($folders) !== count($structure) ) {

                
                if( count($structure) > 0 ) {

                    // Check what folders are missed in structure and return missed folder with last created folder in structure
                    $data = $this->check_exist_folders($structure, $folders);

                    $folders = $data[0];

                    $parent_id = $data[1];

                }

                // Create folders
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
     * Find the file parent folder in already existed structure
     */
    private function get_file_parent($structure, $folders)
    {
        $parent_name = '';

        foreach(array_reverse($folders) as $folder) {
            
            $item = $structure->where('name', $folder);

            $parent = $item->pluck('parent')->pluck('name')[0];

            // Check if folder have valid parent name
            if( $parent && $folder === $parent_name || $parent_name == '') {

                $parent_name = $parent;
            }
       
            return $structure->where('name', $folders[array_key_last($folders)])->first()->id;
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

