<?php
namespace Domain\Teams\Actions;

use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Notification;
use Domain\Teams\Models\TeamFolderInvitation;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;
use Spatie\QueueableAction\QueueableAction;

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
                    'folder_id'  => $folder->id,
                ]);

                // Invite user
                Notification::route('mail', $member['email'])
                    ->notify(new InvitationIntoTeamFolder($folder, $invitation));
            });
    }
}
