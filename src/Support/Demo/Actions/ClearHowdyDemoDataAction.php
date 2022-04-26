<?php
namespace Support\Demo\Actions;

use DB;
use App\Users\Models\User;
use Domain\UploadRequest\Models\UploadRequest;

class ClearHowdyDemoDataAction
{
    public function __invoke()
    {
        // Get howdy account
        $user = User::where('email', 'howdy@hi5ve.digital')
            ->first();

        // Delete howdy shared links
        DB::table('shares')
            ->where('user_id', $user->id)
            ->delete();
    }
}
