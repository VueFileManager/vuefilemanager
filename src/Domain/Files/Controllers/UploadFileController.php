<?php
namespace Domain\Files\Controllers;

use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Support\Demo\Actions\FakeUploadFileAction;
use Domain\Files\Actions\StoreFileChunksAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UploadFileController extends Controller
{
    public function __construct(
        public ProcessFileAction $processFie,
        public FakeUploadFileAction $fakeUploadFile,
        public StoreFileChunksAction $storeFileChunks,
    ) {
    }

    /**
     * Upload file for authenticated master|editor user
     *
     * @throws FileNotFoundException
     */
    public function __invoke(UploadRequest $request)
    {
        if (is_demo_account()) {
            return ($this->fakeUploadFile)($request);
        }

        // Store file chunks
        $chunkPath = ($this->storeFileChunks)($request);

        // Proceed after last chunk
        if ($request->boolean('is_last')) {
            // Process file
            $file = ($this->processFie)($request, null, $chunkPath);

            return response(new FileResource($file), 201);
        }
    }
}
