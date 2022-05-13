<?php
namespace Domain\RemoteUpload\Controllers;

use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Domain\RemoteUpload\Requests\RemoteUploadRequest;
use Domain\RemoteUpload\Actions\GetContentFromExternalSource;

class VisitorRemoteUploadFileController extends Controller
{
    public function __construct(
        public ProtectShareRecordAction $protectShareRecord,
        public VerifyAccessToItemAction $verifyAccessToItem,
        public GetContentFromExternalSource $getContentFromExternalSource,
    ) {
    }

    public function __invoke(
        RemoteUploadRequest $request,
        ?Share $shared = null,
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'Files was successfully uploaded.',
        ];

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            return response()->json(accessDeniedError(), 403);
        }

        // Add default parent id if missing
        if ($request->missing('parent_id')) {
            $request->merge(['parent_id' => $shared->item_id]);
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->input('parent_id'), $shared);

        // If it isn't broadcasting, download files immediately in the request
        if (isNotBroadcasting()) {
            ($this->getContentFromExternalSource)($request->all(), $shared->user);

            return response()->json($successMessage, 201);
        }

        // Add links to the upload queue
        ($this->getContentFromExternalSource)
            ->onQueue()
            ->execute($request->all(), $shared->user);

        return response()->json([
            'type'    => 'success',
            'message' => 'Files were successfully added to the upload queue.',
        ], 201);
    }
}
