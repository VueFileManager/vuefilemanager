<?php


namespace Domain\Teams\Actions;

use Domain\Folders\Models\Folder;
use Domain\Teams\Models\TeamFoldersInvitation;
use Domain\Teams\Notifications\InvitationIntoTeamFolder;
use Illuminate\Support\Facades\Notification;

class InviteMembersIntoTeamFolderAction
{
    public function __invoke(
        array $members,
        Folder $folder,
    ): void {
        collect($members)
            ->each(function ($member) use ($folder) {

                // Create invitation
                $invitation = TeamFoldersInvitation::create([
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