<?php
namespace Domain\RemoteUpload\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\RemoteUploadRequest;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Domain\RemoteUpload\Actions\GetContentFromExternalSource;

class VisitorRemoteUploadFileController extends Controller
{
    public function __construct(
        public ProtectShareRecordAction $protectShareRecord,
        public VerifyAccessToItemAction $verifyAccessToItem,
        public GetContentFromExternalSource $getContentFromExternalSource,
    ) {
    }

    public function __invoke(RemoteUploadRequest $request, ?Share $shared = null): Response|array
    {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403, "You don't have access to this item");
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->input('parent_id'), $shared);

        // Get content from external sources
        if (isBroadcasting()) {
            ($this->getContentFromExternalSource)
                ->onQueue()
                ->execute($request->all(), $shared->user);
        } else {
            ($this->getContentFromExternalSource)($request->all(), $shared->user);
        }

        return response('Files were successfully added to the upload queue', 201);
    }
}
