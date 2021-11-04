<?php
namespace Domain\Files\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Actions\UploadFileAction;
use Support\Demo\Actions\FakeUploadFileAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * guest user upload file into shared folder
 */
class VisitorUploadFileController extends Controller
{
    public function __construct(
        private UploadFileAction $uploadFile,
        private FakeUploadFileAction $fakeUploadFile,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        UploadRequest $request,
        Share $shared,
    ): Response | array {
        if (is_demo_account()) {
            return ($this->fakeUploadFile)($request);
        }

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->input('parent_id'), $shared);

        // Return new uploaded file
        $new_file = ($this->uploadFile)($request, $shared);

        // Set public access url
        $new_file->setPublicUrl($shared->token);

        return response($new_file, 201);
    }
}
