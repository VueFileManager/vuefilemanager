<?php
namespace Domain\Teams\Controllers;

use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Teams\Models\TeamFolderMember;
use Domain\Teams\Models\TeamFolderInvitation;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Teams\Resources\TeamInvitationResource;
use Domain\Teams\Actions\ClearActionInInvitationNotificationAction;

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
        TeamFolderInvitation $invitation,
        ClearActionInInvitationNotificationAction $clearActionInInvitationNotification,
    ): ResponseFactory|Response {
        $user = User::where('email', $invitation->email)
            ->first();

        if ($user) {
            if (is_demo_account()) {
                return response('Done', 204);
            }

            $invitation->accept();

            // Store team member
            TeamFolderMember::create([
                'user_id'    => $user->id,
                'parent_id'  => $invitation->parent_id,
                'permission' => $invitation->permission,
            ]);

            // Clear action in existing notification
            $clearActionInInvitationNotification($user, $invitation);
        }

        if (! $user) {
            $invitation->update([
                'status' => 'waiting-for-registration',
            ]);
        }

        return response('Done', 204);
    }

    public function destroy(
        TeamFolderInvitation $invitation,
        ClearActionInInvitationNotificationAction $clearActionInInvitationNotification,
    ): ResponseFactory|Response {
        $invitation->reject();

        // Get user from invitation
        $user = User::where('email', $invitation->email)
            ->first();

        // Clear action in existing notification
        if ($user) {
            if (is_demo_account()) {
                return response('Done', 204);
            }

            $clearActionInInvitationNotification($user, $invitation);
        }

        return response('Done', 204);
    }
}
