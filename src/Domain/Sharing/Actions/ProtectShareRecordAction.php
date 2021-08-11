<?php
namespace Domain\Sharing\Actions;

use Domain\Sharing\Models\Share;

class ProtectShareRecordAction
{
    private string $message = "Sorry, you don't have permission";

    public function __invoke(
        Share $shared
    ): void {
        if ($shared->is_protected) {
            if (! request()->hasCookie('share_session')) {
                abort(403, $this->message);
            }

            // Get shared session
            $share_session = json_decode(
                request()->cookie('share_session')
            );

            // Check if is requested same share record
            if ($share_session->token !== $shared->token) {
                abort(403, $this->message);
            }

            // Check if share record was authenticated previously via ShareController@authenticate
            if (! $share_session->authenticated) {
                abort(403, $this->message);
            }
        }
    }
}
