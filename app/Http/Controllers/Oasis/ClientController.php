<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Requests\Oasis\StoreClientRequest;
use App\Http\Resources\Oasis\OasisViewClientCollection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\OasisViewClientResource;
use App\Http\Resources\Oasis\OasisViewInvoiceCollection;
use App\Models\Oasis\Client;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        return response(
            new OasisViewClientCollection(Auth::user()->clients), 200
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
            new OasisViewClientResource($client), 201
        );
    }

    /**
     * @param Client $client
     * @return Application|ResponseFactory|Response
     */
    public function show(Client $client)
    {
        return response(new OasisViewClientResource($client), 200);
    }

    /**
     * @param Client $client
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function update(Client $client, Request $request)
    {
        // Store image if exist
        if ($request->hasFile($request->name)) {

            // Find and update image path
            $client->update([
                $request->name => store_avatar($request, $request->name)
            ]);

            return response('Done', 204);
        }

        $client->update(
            make_single_input($request)
        );

        return response('Done', 204);
    }

    /**
     * @param Client $client
     * @throws \Exception
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response('Done', 204);
    }

    /**
     * @param Client $client
     * @return Application|ResponseFactory|Response
     */
    public function show_invoices(Client $client)
    {
        return response(new OasisViewInvoiceCollection($client->invoices), 200);
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
            new OasisViewClientCollection($results), 200
        );
    }
}
