<?php
namespace Domain\Teams\Actions;

use App\Users\Models\User;

class CheckMaxTeamMembersLimitAction
{
    public function __invoke(User $user, array $newInvites): bool
    {
        // Get user limitation summary
        $limits = $user->limitations->summary();

        // Check unlimited option
        if ((int) $limits['max_team_members']['total'] === -1) {
            return true;
        }

        // Get currently used member emails
        $allowedEmails = $limits['max_team_members']['meta']['allowed_emails'];

        // Get new email invites from request
        $invitationEmails = collect($newInvites)
            ->pluck('email');

        // Count total unique members
        $totalMembers = $allowedEmails
            ->merge($invitationEmails)
            ->unique()
            ->count();

        // Check if there is more unique members than total max team members are allowed
        return ! ($totalMembers > $limits['max_team_members']['total']);
    }
}
