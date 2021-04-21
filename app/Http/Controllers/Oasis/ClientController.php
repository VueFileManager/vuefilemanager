<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response(Auth::user()->clients, 200);
    }
}
