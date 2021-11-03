<?php
namespace Domain\Teams\Controllers;

use App\Users\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Domain\Teams\Models\TeamFolderInvitation;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Teams\Resources\TeamInvitationResource;

class InvitationsController extends Controller
{
    public function show(TeamFolderInvitation $invitation)
    {
        if ($invitation->status === 'accepted') {
            abort(410);
        }

        return new TeamInvitationResource($invitation);
    }

    public function update(
        TeamFolderInvitation $invitation
    ): ResponseFactory | Response {
        $user = User::where('email', $invitation->email)
            ->firstOrFail();

        $invitation->update([
            'status' => 'accepted',
        ]);

        DB::table('team_folder_members')
            ->insert([
                'parent_id'  => $invitation->parent_id,
                'user_id'    => $user->id,
                'permission' => 'can-edit',
            ]);

        return response('Done', 204);
    }

    public function destroy(
        TeamFolderInvitation $invitation
    ): ResponseFactory | Response {
        $invitation->update([
            'status' => 'rejected',
        ]);

        return response('Done', 204);
    }
}
