<?php
namespace Domain\Sharing\Actions;

use Domain\Sharing\Models\Share;

class ProtectShareRecordAction
{
    public function __invoke(
        Share $shared
    ): void {
        if ($shared->is_protected) {
            $abort_message = "Sorry, you don't have permission";

            if (! request()->hasCookie('share_session')) {
                abort(403, $abort_message);
            }

            // Get shared session
            $share_session = json_decode(
                request()->cookie('share_session')
            );

            // Check if is requested same share record
            if ($share_session->token !== $shared->token) {
                abort(403, $abort_message);
            }

            // Check if share record was authenticated previously via ShareController@authenticate
            if (! $share_session->authenticated) {
                abort(403, $abort_message);
            }
        }
    }
}
