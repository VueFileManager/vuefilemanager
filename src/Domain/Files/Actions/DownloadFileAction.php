<?php
namespace Domain\Files\Actions;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadFileAction
{
    /**
     * Call and download file
     */
    public function __invoke(File $file): Response|StreamedResponse|RedirectResponse
    {
        // Get file path
        $filePath = "files/$file->user_id/$file->basename";

        // Get pretty name
        $fileName = getPrettyName($file);

        // Check if file exist
        abort_if(Storage::missing($filePath), 404, 'The file not found.');

        // Format response header
        $header = [
            'ResponseAcceptRanges'       => 'bytes',
            'ResponseContentType'        => Storage::mimeType($filePath),
            'ResponseContentLength'      => Storage::size($filePath),
            'ResponseContentRange'       => 'bytes 0-600/' . Storage::size($filePath),
            'ResponseContentDisposition' => "attachment; filename=$fileName",
        ];

        // If s3 redirect to temporary download url
        if (isStorageDriver('s3')) {
            return redirect()->away(Storage::temporaryUrl($filePath, now()->addHour(), $header));
        }

        // Download file
        return Storage::download($filePath, $fileName, $header);
    }
}
