<?php
namespace Domain\Files\Controllers;

use Domain\Files\Models\File;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\UploadFileAction;
use Support\Demo\Actions\FakeUploadFileAction;
use App\Users\Exceptions\InvalidUserActionException;

class UploadFileController extends Controller
{
    public function __construct(
        public UploadFileAction $uploadFiles,
        public FakeUploadFileAction $fakeUploadFile,
    ) {
    }

    /**
     * Upload file for authenticated master|editor user
     */
    public function __invoke(UploadRequest $request)
    {
        if (is_demo_account()) {
            return ($this->fakeUploadFile)($request);
        }

        try {
            // Upload and store file record
            if (! $request->boolean('is_last')) {
                return ($this->uploadFiles)($request);
            }

            $file = ($this->uploadFiles)($request);

            return response(new FileResource($file), 201);
        } catch (InvalidUserActionException $e) {
            return response([
                'type'    => 'error',
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
