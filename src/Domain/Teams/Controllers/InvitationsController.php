<?php
namespace Domain\Teams\Controllers;

use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Teams\Models\TeamFolderMember;
use Domain\Teams\Models\TeamFolderInvitation;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Teams\Resources\TeamInvitationResource;

class InvitationsController extends Controller
{
    public function show(TeamFolderInvitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            abort(410);
        }

        return new TeamInvitationResource($invitation);
    }

    public function update(
        TeamFolderInvitation $invitation
    ): ResponseFactory | Response {
        $user = User::where('email', $invitation->email);

        if ($user->exists()) {
            $invitation->accept();

            // Store team member
            TeamFolderMember::create([
                'user_id'    => $user->first()->id,
                'parent_id'  => $invitation->parent_id,
                'permission' => $invitation->permission,
            ]);
        }

        if ($user->doesntExist()) {
            $invitation->update([
                'status' => 'waiting-for-registration',
            ]);
        }

        return response('Done', 204);
    }

    public function destroy(
        TeamFolderInvitation $invitation
    ): ResponseFactory | Response {
        $invitation->reject();

        return response('Done', 204);
    }
}
