<?php
namespace Domain\Teams\Controllers;

use Auth;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Teams\Requests\ConvertIntoTeamFolderRequest;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;
use Illuminate\Support\Facades\DB;

class ConvertFolderIntoTeamFolderController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
    ) {
    }

    public function __invoke(
        ConvertIntoTeamFolderRequest $request,
        Folder $folder
    ): ResponseFactory | Response {
        $folder->update([
            'team_folder' => 1,
            'parent_id'   => null,
        ]);

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
