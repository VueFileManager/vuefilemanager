<?php
namespace Domain\Files\Actions;

use Domain\Files\Models\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadFileAction
{
    /**
     * Call and download file
     */
    public function __invoke(
        File $file,
        string $user_id,
    ): BinaryFileResponse {
        // Get file path
        $path = "files/$user_id/$file->basename";

        // Check if file exist
        if (! Storage::exists($path)) {
            abort(404);
        }

        // Get pretty name
        $pretty_name = get_pretty_name($file->basename, $file->name, $file->mimetype);

        return response()
            ->download(Storage::path($path), $pretty_name, [
                'Accept-Ranges'       => 'bytes',
                'Content-Type'        => Storage::mimeType($path),
                'Content-Length'      => Storage::size($path),
                'Content-Range'       => 'bytes 0-600/' . Storage::size($path),
                'Content-Disposition' => "attachment; filename=$pretty_name",
            ]);
    }
}
