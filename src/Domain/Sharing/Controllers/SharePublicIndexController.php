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
    private function get_single_image(File $file, string $user_id): StreamedResponse
    {
        // Store user download size
        ($this->recordDownload)($file->getRawOriginal('filesize'), $user_id);

        // Get file path
        $path = "/files/$user_id/$file->basename";

        // Check if file exist
        if (! Storage::exists($path)) {
            abort(404);
        }

        return Storage::response($path, "{$file->name}.{$file->mimetype}", [
            'Content-Type'   => Storage::mimeType($path),
            'Content-Length' => Storage::size($path),
            'Accept-Ranges'  => 'bytes',
            'Content-Range'  => 'bytes 0-600/' . Storage::size($path),
        ]);
    }
}
