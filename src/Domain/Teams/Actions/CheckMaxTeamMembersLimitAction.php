<?php
namespace Domain\Teams\Actions;

use App\Users\Models\User;

class CheckMaxTeamMembersLimitAction
{
    public function __invoke(array $invitations, User $user)
    {
        // Get user limitation summary
        $limits = $user->limitations->summary();

        // Get currently used member emails
        $allowedEmails = $limits['max_team_members']['meta']['allowed_emails'];

        // Get new email invites from request
        $invitationEmails = collect($invitations)
            ->pluck('email');

        // Count total unique members
        $totalMembers = $allowedEmails
            ->merge($invitationEmails)
            ->unique()
            ->count();

        // Check if there is more unique members than total max team members are allowed
        if ($totalMembers > $limits['max_team_members']['total']) {
            abort(423, 'You exceed your members limit.');
        }
    }
}
