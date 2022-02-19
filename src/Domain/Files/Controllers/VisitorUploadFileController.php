<?php
namespace Domain\Files\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\UploadFileAction;
use Support\Demo\Actions\FakeUploadFileAction;
use App\Users\Exceptions\InvalidUserActionException;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

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

    /**
     * @throws FileNotFoundException
     */
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

        try {
            // Return new uploaded file
            $file = ($this->uploadFile)($request, $shared->user_id);

            // Set public access url
            $file->setSharedPublicUrl($shared->token);

            return response(new FileResource($file), 201);
        } catch (InvalidUserActionException $e) {
            return response([
                'type'    => 'error',
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
