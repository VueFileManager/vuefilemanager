<?php
namespace Domain\Sharing\Controllers;

use Illuminate\View\View;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SharePublicIndexController extends Controller
{
    /**
     * Show page index and delete share_session cookie
     */
    public function __construct(
        public RecordDownloadAction $recordDownload,
    ) {
    }

    public function __invoke(
        Share $share,
    ): View | StreamedResponse | RedirectResponse {
        // Check if user can see shared record
        if (! $share->user->canVisitShared()) {
            return redirect('/temporary-unavailable');
        }

        // Delete share_session if exist
        if ($share->is_protected) {
            cookie()->queue('share_session', '', -1);

            return redirect("/share/$share->token/authenticate");
        }

        // Check if shared is image file and then show it
        if ($share->type === 'file') {
            // Get file
            $file = File::whereUserId($share->user_id)
                ->where('type', 'image')
                ->where('id', $share->item_id);

            // Return raw image
            if ($file->exists()) {
                return $this->get_single_image($file->first(), $share->user_id);
            }

            // Return file download page
            return redirect("/share/$share->token/file");
        }

        // Return folder items
        return redirect("/share/$share->token/files/$share->item_id");
    }

    /**
     * Get image from storage and show it
     */
    private function get_single_image(File $file, string $userId): StreamedResponse|RedirectResponse
    {
        // Record user download
        ($this->recordDownload)($file->filesize, $userId);

        // Get file path
        $path = "/files/$userId/$file->basename";

        // Check if file exist
        abort_if(Storage::missing($path), 404);

        // If s3 redirect to temporary download url
        if (isStorageDriver('s3')) {
            return redirect()->away(Storage::temporaryUrl($path, now()->addHour(), [
                'ResponseAcceptRanges'       => 'bytes',
                'ResponseContentType'        => Storage::mimeType($path),
                'ResponseContentLength'      => Storage::size($path),
            ]));
        }

        return Storage::response($path, "{$file->name}.{$file->mimetype}", [
            'Content-Type'   => Storage::mimeType($path),
            'Content-Length' => Storage::size($path),
            'Accept-Ranges'  => 'bytes',
            'Content-Range'  => 'bytes 0-600/' . Storage::size($path),
        ]);
    }
}
