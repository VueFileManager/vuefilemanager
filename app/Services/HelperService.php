<?php

namespace App\Services;

use DB;
use Illuminate\Support\Facades\Storage;

class HelperService
{
    /**
     * Delete all user data including files, folders, avatar etc.
     *
     * @param $user
     */
    public function erase_user_data($user)
    {
        // Delete user avatar if exists
        if ($user->settings->getRawOriginal('avatar')) {
            Storage::delete($user->settings->getRawOriginal('avatar'));
        }

        // Delete all user files
        Storage::deleteDirectory("files/$user->id");

        // Delete all user records in database
        collect(['folders', 'files', 'user_settings', 'shares', 'favourite_folder', 'zips'])
            ->each(function ($table) use ($user) {
                DB::table($table)
                    ->whereUserId($user->id)
                    ->delete();
            });
    }
}