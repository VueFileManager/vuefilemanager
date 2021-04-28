<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\Oasis\InvoiceProfileCollection;
use Illuminate\Http\Request;

class InvoiceProfileController extends Controller
{
    public function store(Request $request)
    {
        $profile = \Auth::user()
            ->invoiceProfile()
            ->create([
                'logo'               => store_avatar($request, 'logo') ?? null,
                'stamp'              => store_avatar($request, 'stamp') ?? null,
                'company'            => $request->company,
                'email'              => $request->email,
                'ico'                => $request->ico,
                'dic'                => $request->dic,
                'ic_dph'             => $request->ic_dph,
                'registration_notes' => $request->registration_notes,
                'author'             => $request->author,
                'address'            => $request->address,
                'state'              => $request->state,
                'city'               => $request->city,
                'postal_code'        => $request->postal_code,
                'country'            => $request->country,
                'phone'              => $request->phone,
                'bank'               => $request->bank,
                'iban'               => $request->iban,
                'swift'              => $request->swift,
            ]);

        return response(
            new InvoiceProfileCollection($profile), 201
        );
    }
}
