<?php

namespace Domain\Sharing\Controllers;

use App\Http\Controllers\Controller;
use Domain\Files\Models\File;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemWithinAction;
use Domain\Sharing\Models\Share;
use Domain\Traffic\Actions\RecordDownloadAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DirectlyDownloadFileController extends Controller
{
    public function __construct(
        private RecordDownloadAction           $recordDownload,
        private ProtectShareRecordAction       $protectShareRecord,
        private VerifyAccessToItemWithinAction $verifyAccessToItemWithin,
    ){}

    public function __invoke(
        Share $share
    ): Response|StreamedResponse|RedirectResponse
    {
        // Check if item is not a folder
        if ($share->type !== 'file') {
            return response('This content is not downloadable');
        }

        // Check ability to access protected share files
        ($this->protectShareRecord)($share);

        // Check if user can download file
        if (!$share->user->canDownload()) {
            return response([
                'type'    => 'error',
                'message' => 'This user action is not allowed.',
            ], 401);
        }

        // Get file record
        $file = File::where('user_id', $share->user_id)
            ->where('id', $share->item_id)
            ->firstOrFail();

        // Check file access
        ($this->verifyAccessToItemWithin)($share, $file);

        // Store user download size
        ($this->recordDownload)(
            file_size: (int)$file->getRawOriginal('filesize'),
            user_id: $share->user_id,
        );

        // Get file path
        $path = "files/$share->user_id/$file->basename";

        // Check if file exist
        if (!Storage::exists($path)) {
            abort(404);
        }

        // Get pretty name
        $pretty_name = get_pretty_name($file->basename, $file->name, $file->mimetype);

        // If s3 redirect to temporary url
        if (is_storage_driver('s3')) {
            return redirect()->away(Storage::temporaryUrl($path, now()->addHour(), [
                'ResponseAcceptRanges'       => 'bytes',
                'ResponseContentType'        => Storage::mimeType($path),
                'ResponseContentLength'      => Storage::size($path),
                'ResponseContentRange'       => 'bytes 0-600/' . Storage::size($path),
                'ResponseContentDisposition' => "attachment; filename=$pretty_name",
            ]));
        }

        return Storage::download($path, $pretty_name);
    }
}