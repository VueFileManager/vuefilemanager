<?php
namespace Domain\Sharing\Controllers;

use Illuminate\View\View;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SharePublicIndexController extends Controller
{
    public function __construct(
        public RecordDownloadAction $recordDownload,
    ) {
    }

    /**
     * Show page index and delete share_session cookie
     */
    public function __invoke(
        Share $shared,
    ): View | StreamedResponse {
        // Delete share_session if exist
        if ($shared->is_protected) {
            cookie()->queue('share_session', '', -1);
        }

        // Check if shared is image file and then show it
        if ($shared->type === 'file' && ! $shared->is_protected) {
            $image = File::whereUserId($shared->user_id)
                ->whereType('image')
                ->whereId($shared->item_id)
                ->first();

            if ($image) {
                // Get image filesize
                $fileSize = (int) $image->getRawOriginal('filesize');

                // Store user download size
                ($this->recordDownload)($fileSize, $shared->user->id);

                return $this->get_single_image($image, $shared->user_id);
            }
        }

        return view('index')
            ->with('installation', 'setup-done')
            ->with('settings', get_settings_in_json() ?? null);
    }

    /**
     * Get image from storage and show it
     */
    private function get_single_image(File $file, string $user_id): StreamedResponse
    {
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
