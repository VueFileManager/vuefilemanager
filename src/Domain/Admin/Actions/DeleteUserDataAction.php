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
        if ($user->subscription) {
            $user->subscription->delete();
        }

        // Delete all user records in database
        collect(['folders', 'files', 'user_settings', 'shares', 'favourite_folder', 'traffic'])
            ->each(function ($table) use ($user) {
                DB::table($table)
                    ->whereUserId($user->id)
                    ->delete();
            });
    }
}
