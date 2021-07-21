<?php
namespace Support\Scheduler\Actions;

use App\Users\Models\User;

class DeleteUnverifiedUsersAction
{
    /**
     * Delete unverified users older than 30 days
     */
    public function __invoke(): void
    {
        User::where('created_at', '<=', now()->subDays(30)->toDateString())
            ->where('email_verified_at', null)
            ->get()
            ->each(fn ($user) => $user->delete());
    }
}
