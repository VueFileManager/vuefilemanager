<?php
namespace Domain\Teams\Controllers;

use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Teams\Models\TeamFolderMember;
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
        // Abort in demo mode
        if (is_demo_account()) {
            return response($folder, 201);
        }

        // Check if user didn't exceed max team members limit
        if (! $folder->user->canInviteTeamMembers($request->input('invitations'))) {
            return response([
                'type'    => 'error',
                'message' => 'You exceed your members limit.',
            ], 401);
        }

        // Update root team folder
        $folder->update([
            'team_folder' => 1,
            'parent_id'   => null,
        ]);

        // Mark all children folders as team folder
        ($this->setTeamFolderPropertyForAllChildren)($folder, true);

        // Attach owner into members
        TeamFolderMember::create([
            'parent_id'  => $folder->id,
            'user_id'    => $folder->user_id,
            'permission' => 'owner',
        ]);

        // Invite team members
        ($this->inviteMembers)($request->input('invitations'), $folder);

        return response($folder, 201);
    }
}
