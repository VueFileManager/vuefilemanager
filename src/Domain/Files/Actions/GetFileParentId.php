<?php
namespace Domain\Files\Actions;

use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Domain\Files\Requests\UploadRequest;

class GetFileParentId
{
    public function __invoke(UploadRequest $request, string $userId): ?string
    {
        // extract file path
        $directoryPath = collect(
            array_slice(explode('/', $request->input('path')), 1, -1)
        );

        // If there isn't directory path, return parent_id
        if ($directoryPath->isEmpty()) {
            return $request->input('parent_id');
        }

        return $this->getOrCreateParentFolders($directoryPath, $userId, $request->input('parent_id'));
    }

    private function getOrCreateParentFolders(Collection $directoryPath, string $userId, ?string $parentId): ?string
    {
        // Break the end of recursive
        if ($directoryPath->isEmpty()) {
            return $parentId;
        }

        // Get requested directory name
        $directoryName = $directoryPath->shift();

        // Get requested directory
        $requestedDirectory = Folder::where('name', $directoryName);

        // Check if root exists, if not, create him
        if ($requestedDirectory->exists()) {
            // Get parent folder
            $parentCheck = Folder::where('name', $directoryName)
                ->where('parent_id', $parentId);

            // Check if parent folder of requested directory name exists, if not, create it
            if ($parentCheck->exists()) {
                $folder = $parentCheck->first();
            } else {
                $folder = $this->createFolder($directoryName, $userId, $parentId);
            }
        }

        // Create directory if not exists
        if ($requestedDirectory->doesntExist()) {
            $folder = $this->createFolder($directoryName, $userId, $parentId);
        }

        // Repeat yourself
        return $this->getOrCreateParentFolders($directoryPath, $userId, $folder->id);
    }

    private function createFolder($directoryName, $userId, $parentId): Folder
    {
        return Folder::create([
            'name'        => $directoryName,
            'parent_id'   => $parentId,
            'user_id'     => $userId,
        ]);
    }
}
