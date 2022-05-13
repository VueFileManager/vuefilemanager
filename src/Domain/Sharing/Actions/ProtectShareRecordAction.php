<?php
namespace Domain\Sharing\Actions;

use Domain\Sharing\Models\Share;

class ProtectShareRecordAction
{
    public function __invoke(
        Share $shared
    ): void {
        if ($shared->is_protected) {
            $errorResponse = [
                'type'    => 'error',
                'message' => "Sorry, you don't have permission",
            ];

            if (! request()->hasCookie('share_session')) {
                abort(
                    response()->json($errorResponse, 403)
                );
            }

            // Get shared session
            $share_session = json_decode(
                request()->cookie('share_session')
            );

            // Check if is requested same share record
            if ($share_session->token !== $shared->token) {
                abort(
                    response()->json($errorResponse, 403)
                );
            }

            // Check if share record was authenticated previously via ShareController@authenticate
            if (! $share_session->authenticated) {
                abort(
                    response()->json($errorResponse, 403)
                );
            }
        }
    }
}
