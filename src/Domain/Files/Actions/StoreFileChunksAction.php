<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Domain\Files\Requests\UploadChunkRequest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class StoreFileChunksAction
{
    /**
     * @throws FileNotFoundException
     */
    public function __invoke(UploadChunkRequest $request)
    {
        // Get uploaded file
        $file = $request->file('chunk');

        // Get chunk name
        $name = $file->getClientOriginalName();

        // Get chunk file path
        $path = Storage::disk('local')->path("chunks/$name");

        // Build the file
        File::append($path, $file->get());

        // If last chunk, then return file path
        if ($request->boolean('is_last_chunk')) {
            return "chunks/$name";
        }
    }
}
