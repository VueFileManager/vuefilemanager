<?php
namespace Domain\Files\Actions;

use Str;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Domain\Files\Requests\UploadRequest;

class CreateFolderStructureAction
{
    public function __invoke(UploadRequest $request, string $userId): string|null
    {
        // extract file path
        $uploadPath = array_slice(explode('/', $request->input('path')), 1, -1);

        // If there isn't folder path, return parent_id
        if (empty($uploadPath)) {
            return $request->input('parent_id');
        }

        // Get already created structure of the file parents
        $structure = Folder::whereIn('name', $uploadPath)
            ->with('parent')
            ->get();

        // If uploading structure has same length as an already existed structure, get correct file parent from the already created structure
        if (count($uploadPath) === count($structure)) {
            return $structure->where('name', $uploadPath[array_key_last($uploadPath)])->first()->id;
        }

        // Check what folders are missed in structure and return missed folder with last created folder in structure
        [$uploadPath, $parentId] = $this->check_exist_folders($structure, $uploadPath);

        // Create folders and return parent_id from last item
        foreach ($uploadPath as $name) {
            $parentId = Folder::create([
                'name'      => $name,
                'user_id'   => $userId,
                'parent_id' => Str::isUuid($parentId) ? $parentId : null,
            ])->id;
        }

        return $parentId;
    }

    /**
     * Return the folders that is need to create in already created structure and last created parent
     */
    private function check_exist_folders(Collection $folders, array $uploadPath): array
    {
        $folderQueue = [];
        $lastParentId = '';

        foreach ($uploadPath as $name) {
            // Filter folders that is needed to create
            if ($folders->doesntContain('name', $name)) {
                array_push($folderQueue, $name);
            }

            // Find last created folder
            if ($folders->contains('name', $name)) {
                $lastParentId = $folders->where('name', $name)->first()->id;
            }
        }

        return [$folderQueue, $lastParentId];
    }
}
