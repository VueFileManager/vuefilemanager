<?php
namespace Domain\UploadRequest\Controllers;

use Domain\Folders\Models\Folder;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Folders\Requests\CreateFolderRequest;
use Support\Demo\Actions\FakeCreateFolderAction;

class CreateFolderController
{
    public function __construct(
        public CreateFolderAction $createFolder,
        public FakeCreateFolderAction $fakeCreateFolder,
    ) {
    }

    public function __invoke(CreateFolderRequest $request, UploadRequest $uploadRequest)
    {
        // Check privileges
        if (! in_array($request->input('parent_id'), getChildrenFolderIds($uploadRequest->id))) {
            return response('Access Denied', 403);
        }

        // Create new folder
        $folder = Folder::create([
            'parent_id'   => $request->input('parent_id'),
            'name'        => $request->input('name'),
            'color'       => $request->input('color') ?? null,
            'emoji'       => $request->input('emoji') ?? null,
            'author'      => 'visitor',
            'user_id'     => $uploadRequest->user_id,
            'team_folder' => false,
        ]);

        // Return new folder
        return response(new FolderResource($folder), 201);
    }
}
