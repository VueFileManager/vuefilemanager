<?php
namespace Domain\Files\Controllers;

use Str;
use Storage;
use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Domain\Files\Requests\UploadFileRequest;
use Support\Demo\Actions\FakeUploadFileAction;
use Domain\Files\Actions\StoreFileChunksAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * guest user upload file into shared folder
 */
class VisitorUploadFileController extends Controller
{
    public function __construct(
        public ProcessFileAction $processFie,
        public StoreFileChunksAction $storeFileChunks,
        private FakeUploadFileAction $fakeUploadFile,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(
        UploadFileRequest $request,
        Share $shared,
    ): JsonResponse {
        if (isDemoAccount()) {
            return response()->json(($this->fakeUploadFile)($request), 201);
        }

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

        // Get file name
        $name = Str::uuid() . '.' . $request->input('extension');

        // Put file to user directory
        Storage::disk('local')->put("files/$shared->user_id/$name", $request->file('file')->get());

        // Process file
        $file = ($this->processFie)($request, $shared->user, $name);

        // Set public access url
        $file->setSharedPublicUrl($shared->token);

        return response()->json(new FileResource($file), 201);
    }
}
