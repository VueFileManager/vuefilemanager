<?php
namespace Domain\Teams\Controllers;

use Domain\Teams\Requests\ConvertIntoTeamFolderRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;

class ConvertFolderIntoTeamFolderController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
    ) {
    }

    public function __invoke(
        ConvertIntoTeamFolderRequest $request,
        Folder $folder
    ): ResponseFactory|Response {
        $folder->update([
            'team_folder' => 1,
            'parent_id'   => null,
        ]);

        // Invite team members
        ($this->inviteMembers)($request->input('invitations'), $folder);

        return response($folder, 201);
    }
}
