<?php
namespace Domain\Teams\Controllers;

use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Teams\Requests\ConvertIntoTeamFolderRequest;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;
use Domain\Teams\Actions\SetTeamFolderPropertyForAllChildrenAction;

class ConvertFolderIntoTeamFolderController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
        public SetTeamFolderPropertyForAllChildrenAction $setTeamFolderPropertyForAllChildren,
    ) {
    }

    public function __invoke(
        ConvertIntoTeamFolderRequest $request,
        Folder $folder
    ): ResponseFactory|Response {
        // Update root team folder
        $folder->update([
            'team_folder' => 1,
            'parent_id'   => null,
        ]);

        ($this->setTeamFolderPropertyForAllChildren)($folder, true);

        // Attach owner into members
        DB::table('team_folder_members')
            ->insert([
                'parent_id'  => $folder->id,
                'user_id'    => $folder->user_id,
                'permission' => 'owner',
            ]);

        // Invite team members
        ($this->inviteMembers)($request->input('invitations'), $folder);

        return response($folder, 201);
    }
}
