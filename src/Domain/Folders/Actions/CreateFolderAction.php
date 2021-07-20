<?php
namespace Domain\Folders\Actions;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;

class CreateFolderAction
{
    /**
     * Create new directory
     */
    public function __invoke(
        $request,
        ?Share $shared = null,
    ): Folder | array {
        return Folder::create([
            'parent_id' => $request->parent_id,
            'name'      => $request->name,
            'color'     => $request->color ?? null,
            'emoji'     => $request->emoji ?? null,
            'author'    => $shared ? 'visitor' : 'user',
            'user_id'   => $shared ? $shared->user_id : Auth::id(),
        ]);
    }
}
