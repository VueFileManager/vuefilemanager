<?php


namespace Domain\Teams\Controllers;


use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Domain\Teams\Models\TeamFoldersInvitation;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AcceptTeamFolderInvitationController extends Controller
{
    public function __invoke(TeamFoldersInvitation $invitation): Response
    {

        $user = User::where('email', $invitation->email)
            ->firstOrFail();

        $invitation->update([
            'status' => 'accepted',
        ]);

        DB::table('team_folder_members')
            ->insert([
                'folder_id'  => $invitation->folder_id,
                'member_id'  => $user->id,
                'permission' => 'can-edit',
            ]);

        return response('Done', 201);
    }
}