<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Domain\Files\Requests\UploadRequest;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Str;

class StoreFileChunksAction
{
    /**
     * @throws FileNotFoundException
     */
    public function __invoke(UploadRequest $request)
    {
        // Get uploaded file
        $file = $request->file('file');

        // Get chunk name
        $name = $file->getClientOriginalName();

        // Get chunk file path
        $path = Storage::disk('local')->path("chunks/$name");

        // Build the file
        File::append($path, $file->get());

        // If last chunk, then return file path
        if ($request->boolean('is_last')) {
            return "chunks/$name";
        }
    }
}
