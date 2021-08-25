<?php
namespace Domain\Teams\Controllers;

use Illuminate\Http\Request;
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
        Request $request,
        Folder $folder
    ): Response {
        $folder->update([
            'team_folder' => 1,
            'parent_id'   => null,
        ]);

        // Invite team members
        ($this->inviteMembers)($request->input('members'), $folder);

        return response($folder, 201);
    }
}
