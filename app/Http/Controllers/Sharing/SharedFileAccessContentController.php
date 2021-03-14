<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Models\File as UserFile;
use App\Models\Zip;
use App\Services\HelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SharedFileAccessContentController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Get generated zip for guest
     *
     * @param $id
     * @param $token
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get_zip_public($id, $token)
    {
        $disk = Storage::disk('local');

        $zip = Zip::where('id', $id)
            ->where('shared_token', $token)
            ->first();

        $zip
            ->user
            ->record_download(
                $disk->size("zip/$zip->basename")
            );

        return $disk
            ->download("zip/$zip->basename", $zip->basename, [
                "Content-Type"        => 'application/zip',
                "Content-Length"      => $disk->size("zip/$zip->basename"),
                "Accept-Ranges"       => "bytes",
                "Content-Range"       => "bytes 0-600/" . $disk->size("zip/$zip->basename"),
                "Content-Disposition" => "attachment; filename=" . $zip->basename,
            ]);
    }

    /**
     * Get file public
     *
     * @param $filename
     * @param $token
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_file_public($filename, $token)
    {
        // Get sharing record
        $shared = get_shared($token);

        // Abort if shared is protected
        if ((int)$shared->is_protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Get file record
        $file = UserFile::where('user_id', $shared->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Check file access
        $this->helper->check_guest_access_to_shared_items($shared, $file);

        // Store user download size
        $shared
            ->user
            ->record_download(
                (int)$file->getRawOriginal('filesize')
            );

        return $this->helper->download_file($file, $shared->user_id);
    }

    /**
     * Get public image thumbnail
     *
     * @param $filename
     * @param $token
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_thumbnail_public($filename, $token)
    {
        // Get sharing record
        $shared = get_shared($token);

        // Abort if thumbnail is protected
        if ((int)$shared->is_protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Get file record
        $file = UserFile::where('user_id', $shared->user_id)
            ->where('thumbnail', $filename)
            ->firstOrFail();

        // Check file access
        $this->helper->check_guest_access_to_shared_items($shared, $file);

        // Store user download size
        $shared
            ->user
            ->record_download(
                (int)$file->getRawOriginal('filesize')
            );

        return $this->helper->download_thumbnail_file($file, $shared->user_id);
    }
}
