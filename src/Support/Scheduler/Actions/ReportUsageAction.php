<?php
namespace Support\Scheduler\Actions;

use DB;
use App\Users\Models\User;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class ReportUsageAction
{
    public function __invoke()
    {
        Subscription::whereIn('type', ['pre-paid', 'auto-renew'])
            ->where('status', 'active')
            ->cursor()
            ->each(function ($subscription) {
                if ($subscription->plan->meteredFeatures()->where('key', 'bandwidth')->exists()) {
                    $this->recordBandwidth($subscription);
                }

                if ($subscription->plan->meteredFeatures()->where('key', 'storage')->exists()) {
                    $this->recordStorageUsage($subscription);
                }

                if ($subscription->plan->meteredFeatures()->where('key', 'flatFee')->exists()) {
                    $this->recordFlatFee($subscription);
                }

                if ($subscription->plan->meteredFeatures()->where('key', 'member')->exists()) {
                    $this->recordMemberUsage($subscription);
                }
            });
    }

    private function recordStorageUsage(Subscription $subscription): void
    {
        // Sum all file size
        $filesize = DB::table('files')
            ->where('user_id', $subscription->user->id)
            ->sum('filesize');

        // We count storage size in GB, e.g. 0.150 is 150mb
        $amount = $filesize / 1_000_000_000;

        // Record storage capacity usage
        $subscription->recordUsage('storage', $amount);
    }

    private function recordBandwidth(Subscription $subscription): void
    {
        // We count storage size in GB, e.g. 0.15 is 150mb
        $record = $subscription
            ->user
            ->traffics()
            ->whereDate('created_at', today()->subDay())
            ->first();

        $amount = (($record->download ?? 0) + ($record->upload ?? 0)) / 1_000_000_000;

        // Record storage capacity usage
        $subscription->recordUsage('bandwidth', $amount);
    }

    private function recordFlatFee(Subscription $subscription): void
    {
        // Record flat fee
        $subscription->recordUsage('flatFee', 1);
    }

    // TODO: Refactor this function to get total used team members, same function here UserLimitation.php@getMaxTeamMembers
    private function recordMemberUsage(Subscription $subscription): void
    {
        $userTeamFolderIds = DB::table('team_folder_members')
            ->where('user_id', $subscription->user_id)
            ->pluck('parent_id');

        $memberIds = DB::table('team_folder_members')
            ->where('user_id', '!=', $subscription->user_id)
            ->whereIn('parent_id', $userTeamFolderIds)
            ->pluck('user_id')
            ->unique();

        // Get member emails
        $memberEmails = User::whereIn('id', $memberIds)
            ->pluck('email');

        // Get active invitation emails
        $InvitationEmails = DB::table('team_folder_invitations')
            ->where('status', 'pending')
            ->where('inviter_id', $subscription->user_id)
            ->pluck('email')
            ->unique();

        // Get allowed emails in the limit
        $totalUsedEmails = $memberEmails->merge($InvitationEmails)
            ->unique();

        $subscription->recordUsage('member', $totalUsedEmails->count());
    }
}
