<?php
namespace App\Http\Controllers\FileManager;

use App\Models\Zip;
use Illuminate\Http\Request;
use App\Services\HelperService;
use App\Models\File as UserFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileAccessController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Get avatar
     *
     * @param $basename
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_avatar($basename)
    {
        // Check if file exist
        if (! Storage::exists("/avatars/$basename")) {
            abort(404);
        }

        // Return avatar
        return Storage::download("/avatars/$basename", $basename);
    }

    /**
     * Get system image
     *
     * @param $basename
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_system_image($basename)
    {
        // Check if file exist
        if (! Storage::exists("/system/$basename")) {
            abort(404);
        }

        // Return avatar
        return Storage::download("/system/$basename", $basename);
    }

    /**
     * Get file
     *
     * @param Request $request
     * @param $filename
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_file(Request $request, $filename)
    {
        // Get file record
        $file = UserFile::withTrashed()
            ->where('user_id', Auth::id())
            ->where('basename', $filename)
            ->firstOrFail();

        // Check user permission
        /*if (!$request->user()->tokenCan('master')) {

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to file
            $this->check_file_access($shared, $file);
        }*/

        // Store user download size
        $request->user()->record_download(
            (int) $file->getRawOriginal('filesize')
        );

        return $this->helper->download_file($file, Auth::id());
    }

    /**
     * Get generated zip for user
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get_zip($id)
    {
        $disk = Storage::disk('local');

        $zip = Zip::whereId($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $zip
            ->user
            ->record_download(
                $disk->size("zip/$zip->basename")
            );

        return $disk->download("zip/$zip->basename", $zip->basename, [
            'Content-Type' => 'application/zip',
            'Content-Length' => $disk->size("zip/$zip->basename"),
            'Accept-Ranges' => 'bytes',
            'Content-Range' => 'bytes 0-600/' . $disk->size("zip/$zip->basename"),
            'Content-Disposition' => "attachment; filename=$zip->basename",
        ]);
    }

    /**
     * Get image thumbnail
     *
     * @param Request $request
     * @param $filename
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_thumbnail(Request $request, $filename)
    {
        // Get file record
        $file = UserFile::withTrashed()
            ->whereUserId(Auth::id())
            ->whereThumbnail($filename)
            ->firstOrFail();

        // Check user permission
        /*if (!$request->user()->tokenCan('master')) {
            $this->check_file_access($request, $file);
        }*/

        return $this->helper->download_thumbnail_file($file, Auth::id());
    }
}
