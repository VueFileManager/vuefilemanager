<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Oasis\StoreInvoiceRequest;
use App\Http\Resources\Oasis\OasisViewInvoiceCollection;
use App\Http\Resources\Oasis\OasisViewInvoiceResource;
use App\Models\Oasis\Client;
use App\Models\Oasis\Invoice;
use App\Notifications\Oasis\InvoiceDeliveryNotification;
use App\Notifications\SharedSendViaEmail;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Storage;

class InvoiceController extends Controller
{
    /**
     * @return mixed
     */
    public function get_all_regular_invoices()
    {
        return response(
            new OasisViewInvoiceCollection(Auth::user()->regularInvoices), 200
        );
    }

    /**
     * @return mixed
     */
    public function get_all_advance_invoices()
    {
        return response(
            new OasisViewInvoiceCollection(Auth::user()->advanceInvoices), 200
        );
    }

    /**
     * @param Invoice $invoice
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get_invoice(Invoice $invoice)
    {
        if (!Storage::exists(invoice_path($invoice))) {
            abort(404, 'Not Found');
        }

        return Storage::download(invoice_path($invoice), "invoice-{$invoice->id}.pdf");
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
            new OasisViewInvoiceCollection($results), 200
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
        $user = $request->user();

        $invoice = Invoice::create([
            'user_id'         => $user->id,
            'client_id'       => $client->id ?? null,
            'invoice_type'    => $request->invoice_type,
            'invoice_number'  => $request->invoice_number,
            'variable_number' => $request->variable_number,
            'delivery_at'     => $request->delivery_at,
            'discount_type'   => $request->discount_type ?? null,
            'discount_rate'   => $request->discount_rate ?? null,
            'items'           => $request->items,
            'user'            => $user->invoiceProfile,
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

        // Generate PDF
        \PDF::loadView('oasis.invoices.invoice', [
            'invoice' => Invoice::find($invoice->id),
            'user'    => $user,
        ])
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->save(
                storage_path("app/files/{$user->id}/invoice-{$invoice->id}.pdf")
            );

        if ($request->send_invoice && $invoice->client['email']) {
            Notification::route('mail', $invoice->client['email'])->notify(
                new InvoiceDeliveryNotification($user, $invoice)
            );
        }

        return response(
            new OasisViewInvoiceResource($invoice), 201
        );
    }

    /**
     * @param Invoice $invoice
     * @throws \Exception
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response('Done', 204);
    }

    /**
     * @param StoreInvoiceRequest $request
     * @return mixed
     */
    private function getOrStoreClient(StoreInvoiceRequest $request)
    {
        if (!Str::isUuid($request->client) && $request->store_client) {

            return $request->user()
                ->clients()
                ->create([
                    'avatar'       => store_avatar($request, 'client_avatar') ?? null,
                    'name'         => $request->client_name,
                    'email'        => $request->client_email ?? null,
                    'phone_number' => $request->client_phone_number ?? null,
                    'address'      => $request->client_address,
                    'city'         => $request->client_city,
                    'postal_code'  => $request->client_postal_code,
                    'country'      => $request->client_country,
                    'ico'          => $request->client_ico ?? null,
                    'dic'          => $request->client_dic ?? null,
                    'ic_dph'       => $request->client_ic_dph ?? null,
                ]);
        }

        return Client::whereUserId($request->user()->id)
            ->whereId($request->client)
            ->first();
    }
}
