<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\OasisClientCollection;
use Auth;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response(
            new OasisClientCollection(Auth::user()->clients), 200
        );
    }
}
