<?php
namespace Domain\Sharing\Controllers;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BrowseShareController extends Controller
{
    public function __construct(
        private HelperService $helper,
    ) {
    }

    /**
     * Show page index and delete access_token & shared_token cookie
     * @param Share $shared
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function index(Share $shared)
    {
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
                // Store user download size
                $shared
                    ->user
                    ->recordDownload(
                        (int) $image->getRawOriginal('filesize')
                    );

                return $this->get_single_image($image, $shared->user_id);
            }
        }

        return view('index')
            ->with('installation', 'setup-done')
            ->with('settings', get_settings_in_json() ?? null);
    }

    /**
     * Get image from storage and show it
     *
     * @param $file
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function get_single_image($file, $user_id)
    {
        // Format pretty filename
        $file_pretty_name = $file->name . '.' . $file->mimetype;

        // Get file path
        $path = "/files/$user_id/$file->basename";

        // Check if file exist
        if (! Storage::exists($path)) {
            abort(404);
        }

        return Storage::response($path, $file_pretty_name, [
            'Content-Type'   => Storage::mimeType($path),
            'Content-Length' => Storage::size($path),
            'Accept-Ranges'  => 'bytes',
            'Content-Range'  => 'bytes 0-600/' . Storage::size($path),
        ]);
    }
}
