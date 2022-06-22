<?php

namespace Domain\Admin\Actions;

use DB;
use App\Users\Models\User;
use Illuminate\Support\Facades\Storage;

class DeleteUserDataAction
{
    /**
     * Delete all user data including files, folders, avatar etc.
     */
    public function __invoke(User $user)
    {
        // Delete user avatar if exists
        if ($user->settings->getRawOriginal('avatar')) {
            // TODO: delete all generated avatars
            Storage::delete($user->settings->getRawOriginal('avatar'));
        }

        // Delete all user files
        Storage::deleteDirectory("files/$user->id");

        // Delete user subscriptions
        $user->subscription?->delete();

        // Delete all user records in database
        collect([
            'team_folder_members',
            'favourite_folder',
            'user_limitations',
            'upload_requests',
            'billing_alerts',
            'user_settings',
            'credit_cards',
            'customers',
            //'dunnings',
            'folders',
            'traffic',
            'shares',
            'files',
        ])
            ->each(function ($table) use ($user) {
                DB::table($table)
                    ->where('user_id', $user->id)
                    ->delete();
            });
    }
}
