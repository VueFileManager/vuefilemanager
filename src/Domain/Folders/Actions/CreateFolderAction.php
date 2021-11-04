<?php
namespace Domain\Folders\Actions;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Domain\Folders\Requests\CreateFolderRequest;

class CreateFolderAction
{
    /**
     * Create new folder
     */
    public function __invoke(
        CreateFolderRequest $request,
        ?Share $shared = null,
    ): Folder|array {
        /*
         * check if exist parent team folder, if yes,
         * then get folder to detect whether it is team_folder
        */
        if ($request->has('parent_id')) {
            $isTeamFolder = Folder::find(
                $request->input('parent_id')
            )
                ->getLatestParent()
                ->team_folder;
        }

        // Create folder record
        return Folder::create([
            'parent_id'   => $request->input('parent_id'),
            'name'        => $request->input('name'),
            'color'       => $request->input('color') ?? null,
            'emoji'       => $request->input('emoji') ?? null,
            'author'      => $shared ? 'visitor' : 'user',
            'user_id'     => $shared ? $shared->user_id : Auth::id(),
            'team_folder' => $isTeamFolder ?? false,
        ]);
    }
}
