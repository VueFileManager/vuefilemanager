<?php
namespace Domain\Teams\Actions;

use App\Users\Models\User;
use Domain\Folders\Models\Folder;
use Spatie\QueueableAction\QueueableAction;
use Illuminate\Support\Facades\Notification;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;

class InviteMembersIntoTeamFolderAction
{
    use QueueableAction;

    public function __invoke(
        array $members,
        Folder $folder,
    ): void {
        collect($members)
            ->each(function ($member) use ($folder) {
                // Create invitation
                $invitation = TeamFolderInvitation::create([
                    'permission' => $member['permission'],
                    'email'      => $member['email'],
                    'parent_id'  => $folder->id,
                    'inviter_id' => $folder->user_id,
                ]);

                // Get user
                $user = User::where('email', $member['email'])->first();

                // Invite native user
                if ($user) {
                    $user->notify(new InvitationIntoTeamFolder($folder, $invitation));
                }

                // Invite guest
                if (! $user) {
                    // Get default app locale
                    $appLocale = get_settings('language') ?? 'en';

                    Notification::route('mail', $member['email'])
                        ->notify(
                            (new InvitationIntoTeamFolder($folder, $invitation))->locale($appLocale)
                        );
                }
            });
    }
}
