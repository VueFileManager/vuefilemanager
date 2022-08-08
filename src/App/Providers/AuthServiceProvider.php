<?php
namespace App\Providers;

use DB;
use Auth;
use App\Users\Models\User;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCustomVerificationEmail();
        $this->registerPolicies();
        $this->registerGates();
    }

    private function shareGuard(Share $share, Folder | File $item): bool
    {
        if (! $share->is_protected) {
            return true;
        }

        if (! request()->hasCookie('share_session')) {
            return false;
        }

        // Get shared session
        $share_session = json_decode(
            request()->cookie('share_session')
        );

        // Check if is requested same share record
        if ($share_session->token !== $share->token) {
            return false;
        }

        // Check if share record was previously authenticated
        if (! $share_session->authenticated) {
            return false;
        }

        return $share->user_id === $item->user_id;
    }

    private function teamMemberGuard(Folder | File $item, ?User $user, $ability): bool
    {
        $teamFolder = $item->getLatestParent();

        $membership = DB::table('team_folder_members')
            ->where('parent_id', $teamFolder->id)
            ->where('user_id', $user->id)
            ->first();

        // check existing members permission or check team folder owner privileges
        return $membership?->permission === $ability || $teamFolder->user_id === Auth::id();
    }

    private function registerGates(): void
    {
        // Define admin maintenance gate
        Gate::define('maintenance', fn($user) => $user->role === 'admin');

        // Define user ability to edit file or folder
        collect(['can-edit', 'can-view'])
            ->each(function ($ability) {
                Gate::define($ability, function (?User $user, File|Folder $item, ?Share $share) use ($ability) {
                    // If share link exist, then check share access
                    if ($share) {
                        return $this->shareGuard($share, $item);
                    }

                    // Check user owner status
                    if ($user?->id === $item->user_id) {
                        return true;
                    }

                    // Check team member ability to access into requested item
                    return $this->teamMemberGuard($item, $user, $ability);
                });
            });

        // Define owner of file or folder
        Gate::define('owner', function (?User $user, File|Folder $item) {
            // Check user owner status
            return $user?->id === $item->user_id;
        });
    }

    private function registerCustomVerificationEmail(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject(__t('verify_email_subject'))
                ->line(__t('verify_email_line'))
                ->action(__t('verify_email_action'), $url);
        });
    }
}
