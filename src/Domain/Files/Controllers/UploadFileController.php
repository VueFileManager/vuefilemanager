<?php
namespace Domain\Files\Controllers;

use Domain\Files\Models\File;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\UploadRequest;
use Domain\Files\Actions\UploadFileAction;
use Support\Demo\Actions\FakeUploadFileAction;

class UploadFileController extends Controller
{
    /**
     * Upload file for authenticated master|editor user
     */
    public function __invoke(
        UploadRequest $request,
        UploadFileAction $uploadFiles,
        FakeUploadFileAction $fakeUploadFile,
    ): File | array {
        if (is_demo_account('howdy@hi5ve.digital')) {
            return ($fakeUploadFile)($request);
        }

        return ($uploadFiles)($request);
    }
}
