<?php
namespace Domain\Files\Controllers;

use Storage;
use Illuminate\Support\Str;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Domain\Files\Requests\UploadFileRequest;
use Support\Demo\Actions\FakeUploadFileAction;

class UploadFileController extends Controller
{
    public function __construct(
        public ProcessFileAction $processFie,
        public FakeUploadFileAction $fakeUploadFile,
    ) {
    }

    public function __invoke(
        UploadFileRequest $request
    ): JsonResponse {
        if (isDemoAccount()) {
            return response()->json(($this->fakeUploadFile)($request), 201);
        }

        // Get user
        $user = $request->filled('parent_id')
            ? Folder::find($request->input('parent_id'))
                ->getLatestParent()
                ->user
            : auth()->user();

        // Get file name
        $name = Str::uuid() . '.' . $request->input('extension');

        // Put file to user directory
        Storage::disk('local')->put("files/$user->id/$name", $request->file('file')->get());

        // Process file
        $file = ($this->processFie)($request, $user, $name);

        return response()->json(new FileResource($file), 201);
    }
}
