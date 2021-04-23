<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Requests\Oasis\StoreClientRequest;
use App\Http\Resources\Oasis\OasisClientCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\OasisClientResource;
use App\Models\Oasis\Client;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

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

    /**
     * @param StoreClientRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function store(StoreClientRequest $request)
    {
        $client = $request->user()
            ->clients()
            ->create([
                'avatar'       => store_avatar($request, 'avatar') ?? null,
                'name'         => $request->name,
                'email'        => $request->email ?? null,
                'phone_number' => $request->phone_number ?? null,
                'address'      => $request->address,
                'city'         => $request->city,
                'postal_code'  => $request->postal_code,
                'country'      => $request->country,
                'ico'          => $request->ico ?? null,
                'dic'          => $request->dic ?? null,
                'ic_dph'       => $request->ic_dph ?? null,
            ]);

        return response(
            new OasisClientResource($client), 201
        );
    }
}
