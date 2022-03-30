<?php
namespace Domain\Folders\Actions;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Folders\Requests\CreateFolderRequest;
use App\Users\Exceptions\InvalidUserActionException;

class CreateFolderAction
{
    /**
     * Create new folder
     *
     * @throws InvalidUserActionException
     */
    public function __invoke(
        CreateFolderRequest $request,
        ?Share $shared = null,
    ): Folder|array {
        // Get stuff
        $isFilledParentId = $request->filled('parent_id');
        $parentId = $request->input('parent_id');

        // Get user
        $user = $isFilledParentId
            ? Folder::find($parentId)->getLatestParent()->user
            : auth()->user();

        // Check if user can create folder
        if (! $user->canCreateFolder()) {
            throw new InvalidUserActionException();
        }

        // Create folder record
        return Folder::create([
            'parent_id'   => $parentId,
            'name'        => $request->input('name'),
            'color'       => $request->input('color') ?? null,
            'emoji'       => $request->input('emoji') ?? null,
            'author'      => $shared ? 'visitor' : 'user',
            'user_id'     => $user->id,
            'team_folder' => $isFilledParentId
                ? Folder::find($parentId)->getLatestParent()->team_folder
                : false,
        ]);
    }
}
