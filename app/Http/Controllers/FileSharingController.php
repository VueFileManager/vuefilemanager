<?php

namespace App\Http\Controllers;

use App\FileManagerFile;
use App\FileManagerFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileSharingController extends Controller
{
    /**
     * Generate file share link
     *
     * @param Request $request
     * @return array
     */
    public function generate_link(Request $request) {

        return 'http://192.168.1.131:8000/shared?token=' . Str::random(64);
    }
    /**
     * Check Password for protected item
     *
     * @param Request $request
     * @return array
     */
    public function check_password(Request $request) {

        return $request->all();
    }


    public function get_shared(Request $request) {

        // Get user
        $user_id = Auth::id();

        // Get folders and files
        $folders = FileManagerFolder::with('parent')
            ->where('user_id', $user_id)
            ->where('parent_id', 0)
            ->get();

        $files = FileManagerFile::with('parent')
            ->where('user_id', $user_id)
            ->where('folder_id', 0)
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }
}
