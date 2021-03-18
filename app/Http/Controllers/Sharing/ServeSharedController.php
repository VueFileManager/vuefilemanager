<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\AuthenticateShareRequest;
use App\Http\Resources\ShareResource;
use App\Models\Share;
use App\Models\Setting;
use App\Services\HelperService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Folder;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ServeSharedController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Show page index and delete access_token & shared_token cookie
     *
     * @param Share $shared
     * @return \Illuminate\Http\Response
     */
    public function index(Share $shared)
    {
        // Delete share_session if exist
        if ($shared->is_protected) {
            cookie()->queue('share_session', '', -1);
        }

        // Check if shared is image file and then show it
        if ($shared->type === 'file' && !$shared->is_protected) {

            $image = File::whereUserId($shared->user_id)
                ->whereType('image')
                ->whereId($shared->item_id)
                ->firstOrFail();

            // Store user download size
            $shared
                ->user
                ->record_download(
                    (int)$image->getRawOriginal('filesize')
                );

            return $this->show_image($image, $shared->user_id);
        }

        return view("index")
            ->with('settings', get_settings_in_json() ?? null);
    }

    /**
     * Check Password for protected item
     *
     * @param AuthenticateShareRequest $request
     * @param Share $shared
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function authenticate(AuthenticateShareRequest $request, Share $shared)
    {
        // Check password
        if (!Hash::check($request->password, $shared->password)) {
            abort(401, __('vuefilemanager.incorrect_password'));
        }

        // Return authorize token with shared options
        return response(new ShareResource($shared), 200)
            ->cookie('share_session', json_encode([
                'token' => $shared->token,
                'authenticated' => true,
            ]), 43200);
    }

    /**
     * Get image from storage and show it
     *
     * @param $file
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function show_image($file, $user_id)
    {
        // Format pretty filename
        $file_pretty_name = $file->name . '.' . $file->mimetype;

        // Get file path
        $path = "/files/$user_id/$file->basename";

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        return Storage::response($path, $file_pretty_name, [
            "Content-Type"   => Storage::mimeType($path),
            "Content-Length" => Storage::size($path),
            "Accept-Ranges"  => "bytes",
            "Content-Range"  => "bytes 0-600/" . Storage::size($path),
        ]);
    }

    /**
     * Get shared public file record
     *
     * @param Share $shared
     * @return mixed
     */
    public function file_public(Share $shared)
    {
        // Abort if file is protected
        if ($shared->is_protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Get file
        $file = File::where('user_id', $shared->user_id)
            ->where('id', $shared->item_id)
            ->firstOrFail(['name', 'basename', 'thumbnail', 'type', 'filesize', 'mimetype']);

        // Set urls
        $file->setPublicUrl($shared->token);

        // Return record
        return $file;
    }
}
