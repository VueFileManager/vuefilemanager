<?php
namespace Domain\Files\Controllers;

use Storage;
use Illuminate\Support\Str;
use Domain\Folders\Models\Folder;
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
            // Get user
            $user = $request->filled('parent_id')
                ? Folder::find($request->input('parent_id'))
                    ->getLatestParent()
                    ->user
                : auth()->user();

            // Get file name
            $name = Str::uuid() . '.' . $request->input('extension');

            // Move file to user directory
            Storage::disk('local')->move($chunkPath, "files/$user->id/$name");

            // Process file
            $file = ($this->processFie)($request, $user, $name);

            return response(new FileResource($file), 201);
        }
    }
}
