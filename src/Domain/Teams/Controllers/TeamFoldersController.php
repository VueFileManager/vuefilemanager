<?php

namespace Domain\Teams\Controllers;

use DB;
use Domain\Teams\Actions\InviteMembersIntoTeamFolderAction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Teams\DTO\CreateTeamFolderData;

class TeamFoldersController extends Controller
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
    ) {}

    public function store(
        Request $request,
    ): Response {
        $data = CreateTeamFolderData::fromRequest($request);

        $folder = Folder::create([
            'user_id'     => $request->user()->id,
            'name'        => $data->name,
            'team_folder' => 1,
        ]);

        // Invite team members
        ($this->inviteMembers)($data->members, $folder);

        return response($folder, 201);
    }

    public function destroy(Folder $folder): Response
    {
        $folder->update([
            'team_folder' => 0,
        ]);

        DB::table('team_folder_members')
            ->where('folder_id', $folder->id)
            ->delete();

        return response('Done.', 204);
    }
}
