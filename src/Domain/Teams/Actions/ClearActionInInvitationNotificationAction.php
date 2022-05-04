<?php
namespace Domain\Teams\Actions;

use DB;
use App\Users\Models\User;
use Domain\Teams\Models\TeamFolderInvitation;

class ClearActionInInvitationNotificationAction
{
    public function __invoke(User $user, TeamFolderInvitation $invitation): void
    {
        if (isDemoAccount()) {
            return;
        }

        // Get notification with invitation
        $notification = DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->where('data', 'LIKE', "%{$invitation->id}%")
            ->first();

        if ($notification) {
            // Get data
            $data = json_decode($notification->data);

            // Clear action object
            $data->action = null;

            // Update notification
            DB::table('notifications')
                ->where('notifiable_id', $user->id)
                ->where('data', 'LIKE', "%{$invitation->id}%")
                ->update([
                    'data' => json_encode($data),
                ]);
        }
    }
}
