<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Oasis\StoreInvoiceRequest;
use App\Http\Resources\Oasis\OasisInvoiceCollection;
use App\Http\Resources\Oasis\OasisInvoiceResource;
use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use App\Notifications\Oasis\InvoiceDeliveryNotification;
use App\Notifications\SharedSendViaEmail;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * @return mixed
     */
    public function get_all_regular_invoices()
    {
        return response(
            new OasisInvoiceCollection(Auth::user()->regularInvoices), 200
        );
    }

    /**
     * @return mixed
     */
    public function get_all_advance_invoices()
    {
        return response(
            new OasisInvoiceCollection(Auth::user()->advanceInvoices), 200
        );
    }

    /**
     * @return mixed
     */
    public function search()
    {
        $query = remove_accents(request()->input('query'));

        $results = Invoice::search($query)
            ->where('user_id', request()->user()->id)
            ->where('invoice_type', request()->input('type'))
            ->get();

        return response(
            new OasisInvoiceCollection($results), 200
        );
    }

    /**
     * Store and generate new invoice
     *
     * @param StoreInvoiceRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        $client = $this->getOrStoreClient($request);

        $invoice = Invoice::create([
            'user_id'         => $request->user()->id,
            'invoice_type'    => $request->invoice_type,
            'invoice_number'  => $request->invoice_number,
            'variable_number' => $request->variable_number,
            'discount_type'   => $request->discount_type ?? null,
            'discount_rate'   => $request->discount_rate ?? null,
            'delivery_at'     => $request->delivery_at,
            'items'           => $request->items,
            'client_id'       => $client->id ?? null,
            'client'          => [
                'email'       => $client->email ?? $request->client_email,
                'name'        => $client->name ?? $request->client_name,
                'address'     => $client->address ?? $request->client_address,
                'city'        => $client->city ?? $request->client_city,
                'postal_code' => $client->postal_code ?? $request->client_postal_code,
                'country'     => $client->country ?? $request->client_country,
                'ico'         => $client->ico ?? $request->client_ico,
                'dic'         => $client->dic ?? $request->client_dic ?? null,
                'ic_dph'      => $client->ic_dph ?? $request->client_ic_dph ?? null,
            ],
        ]);

        if ($request->send_invoice && $invoice->client['email']) {

            Notification::route('mail', $invoice->client['email'])
                ->notify(new InvoiceDeliveryNotification($request->user()));
        }

        return response(
            new OasisInvoiceResource($invoice), 201
        );
    }

    /**
     * @param StoreInvoiceRequest $request
     * @return mixed
     */
    private function getOrStoreClient(StoreInvoiceRequest $request)
    {
        // Store new client
        if (!Str::isUuid($request->client) && $request->store_client) {

            return $request->user()->clients()->create([
                'avatar'       => store_avatar($request, 'client_avatar') ?? null,
                'name'         => $request->client_name,
                'email'        => $request->client_email ?? null,
                'phone_number' => $request->client_phone_number ?? null,
                'address'      => $request->client_address,
                'city'         => $request->client_city,
                'postal_code'  => $request->client_postal_code,
                'country'      => $request->client_country,
                'ico'          => $request->client_ico,
                'dic'          => $request->client_dic ?? null,
                'ic_dph'       => $request->client_ic_dph ?? null,
            ]);
        }

        // Get client
        if (Str::isUuid($request->client)) {

            return Client::whereUserId($request->user()->id)
                ->whereId($request->client)
                ->first();
        }
    }
}
