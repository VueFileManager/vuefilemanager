<?php
namespace Domain\Teams\Controllers;

use App\Users\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Teams\Models\TeamFolderMember;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Teams\Resources\TeamInvitationResource;
use Domain\Teams\Actions\ClearActionInInvitationNotificationAction;

class InvitationsController extends Controller
{
    public function show(
        TeamFolderInvitation $invitation
    ): JsonResponse {
        // Check if invitation is not pending
        if ($invitation->status !== 'pending') {
            return response()->json([
                'type'    => 'error',
                'message' => 'Invitation was already used.',
            ], 410);
        }

        return response()->json(new TeamInvitationResource($invitation));
    }

    public function update(
        TeamFolderInvitation $invitation,
        ClearActionInInvitationNotificationAction $clearActionInInvitationNotification,
    ): JsonResponse {
        // Check if invitation has other state than pending
        if ($invitation->status !== 'pending') {
            return response()->json([
                'type'    => 'error',
                'message' => 'The invitation was previously used.',
            ], 422);
        }

        // Prepare success message
        $successMessage = [
            'type'    => 'success',
            'message' => 'Invitation was accepted.',
        ];

        // Get invited user
        $user = User::where('email', $invitation->email)
            ->first();

        if ($user) {
            if (isDemoAccount()) {
                return response()->json($successMessage);
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

        return response()->json($successMessage);
    }

    public function destroy(
        TeamFolderInvitation $invitation,
        ClearActionInInvitationNotificationAction $clearActionInInvitationNotification,
    ): JsonResponse {
        // Check if invitation has other state than pending
        if ($invitation->status !== 'pending') {
            return response()->json([
                'type'    => 'error',
                'message' => 'The invitation was previously used.',
            ], 422);
        }

        // Prepare success message
        $successMessage = [
            'type'    => 'success',
            'message' => 'Invitation was declined.',
        ];

        $invitation->reject();

        // Get user from invitation
        $user = User::where('email', $invitation->email)
            ->first();

        // Clear action in existing notification
        if ($user) {
            if (isDemoAccount()) {
                return response()->json($successMessage);
            }

            $clearActionInInvitationNotification($user, $invitation);
        }

        return response()->json($successMessage);
    }
}
