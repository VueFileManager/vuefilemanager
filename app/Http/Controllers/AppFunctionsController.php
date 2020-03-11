<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Response;

class AppFunctionsController extends Controller
{
    /**
     * Show index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("index");
    }

    /**
     * Get file
     *
     * @param $filename
     * @return mixed
     */
    public function get_avatar($basename)
    {
        // Get file path
        $path = storage_path() . '/app/avatars/' . $basename;

        // Check if file exist
        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);

        // Create response
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
