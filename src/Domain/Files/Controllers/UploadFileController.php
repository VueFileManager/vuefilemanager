<?php
namespace Domain\Files\Controllers;

use Domain\Files\Models\File;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Actions\UploadFileAction;

class UploadFileController extends Controller
{
    /**
     * Upload file for authenticated master|editor user
     */
    public function __invoke(
        UploadRequest $request,
        UploadFileAction $uploadFiles,
    ): File {
        if (is_demo_account('howdy@hi5ve.digital')) {
            return $this->demo->upload($request);
        }

        return ($uploadFiles)($request);
    }
}
