<?php


namespace Domain\Files\Controllers;


use App\Http\Controllers\Controller;
use Domain\Files\Actions\UploadFilesAction;
use Domain\Files\Models\File;
use Domain\Files\Requests\UploadRequest;

class UploadFilesController extends Controller
{
    /**
     * Upload file for authenticated master|editor user
     */
    public function __invoke(
        UploadRequest $request,
        UploadFilesAction $uploadFiles,
    ): File {
        if (is_demo_account('howdy@hi5ve.digital')) {
            return $this->demo->upload($request);
        }

        return ($uploadFiles)($request);
    }
}