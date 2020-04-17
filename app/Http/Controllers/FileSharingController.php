<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
