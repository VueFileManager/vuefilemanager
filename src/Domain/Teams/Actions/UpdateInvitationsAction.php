<?php
namespace Domain\Teams\Actions;

use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;

class UpdateInvitationsAction
{
    public function __construct(
        public InviteMembersIntoTeamFolderAction $inviteMembers,
    ) {
    }

    public function __invoke(Folder $folder, $invitations): void
    {
        // Get stored invitations from team folder
        $storedInvitations = $folder
            ->teamInvitations()
            ->pluck('email');

        // Get newbies added by user in request
        $newbies = collect($invitations)
            ->filter(
                fn ($invitation) => ! in_array($invitation['email'], $storedInvitations->toArray())
            );

        // Get deleted invitations by user in request
        $removed = $storedInvitations->diff(
            collect($invitations)->pluck('email')->toArray()
        );

        // Invite team members
        if ($newbies->isNotEmpty()) {
            $this->inviteMembers->onQueue()->execute($newbies->toArray(), $folder);
        }

        // Delete invite from team folder
        if ($removed->isNotEmpty()) {
            DB::table('team_folder_invitations')
                ->where('parent_id', $folder->id)
                ->whereIn('email', $removed)
                ->delete();
        }

        // Update privileges
        collect($invitations)
            ->each(
                fn ($invitation) =>
                DB::table('team_folder_invitations')
                    ->where('parent_id', $folder->id)
                    ->where('email', $invitation['email'])
                    ->update([
                        'permission' => $invitation['permission'],
                    ])
            );
    }
}
