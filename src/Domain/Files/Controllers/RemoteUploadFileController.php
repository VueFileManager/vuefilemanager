<?php
namespace Domain\Files\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Files\Requests\RemoteUploadRequest;
use Domain\Files\Actions\GetContentFromExternalSource;

class RemoteUploadFileController extends Controller
{
    public function __construct(
        public GetContentFromExternalSource $getContentFromExternalSource,
    ) {
    }

    public function __invoke(RemoteUploadRequest $request, ?Share $shared = null): Response|array
    {
        if (is_demo_account()) {
            return response('Files were successfully added to the upload queue', 201);
        }

        // Get user
        $user = $request->filled('parent_id')
            ? Folder::find($request->input('parent_id'))->getLatestParent()->user
            : auth()->user();

        // Execute job for get content from url and save
        ($this->getContentFromExternalSource)($request->all(), $user);

        return response('Files were successfully added to the upload queue', 201);
    }
}
