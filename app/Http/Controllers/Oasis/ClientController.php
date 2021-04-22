<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Resources\Oasis\OasisClientCollection;
use App\Http\Controllers\Controller;
use App\Models\Oasis\Client;
use Auth;

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

    /**
     * @return mixed
     */
    public function search()
    {
        $query = remove_accents(request()->input('query'));

        $results = Client::search($query)
            ->where('user_id', request()->user()->id)
            ->get();

        return response(
            new OasisClientCollection($results), 200
        );
    }
}
