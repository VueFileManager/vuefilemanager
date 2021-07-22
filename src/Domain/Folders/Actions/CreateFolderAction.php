<?php
namespace Domain\Folders\Actions;

use Domain\Folders\Requests\CreateFolderRequest;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;

class CreateFolderAction
{
    /**
     * Create new directory
     */
    public function __invoke(
        CreateFolderRequest $request,
        ?Share $shared = null,
    ): Folder | array {
        return Folder::create([
            'parent_id' => $request->input('parent_id'),
            'name'      => $request->input('name'),
            'color'     => $request->input('color') ?? null,
            'emoji'     => $request->input('emoji') ?? null,
            'author'    => $shared ? 'visitor' : 'user',
            'user_id'   => $shared ? $shared->user_id : Auth::id(),
        ]);
    }
}
