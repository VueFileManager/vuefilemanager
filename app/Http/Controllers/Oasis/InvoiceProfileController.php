<?php
namespace App\Http\Controllers\Oasis;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Oasis\InvoiceProfile;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Resources\Oasis\InvoiceProfileResource;

class InvoiceProfileController extends Controller
{
    /**
     * @return Application|ResponseFactory|Response
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->invoiceProfile) {

            return response(
                new InvoiceProfileResource($user->invoiceProfile),
                200
            );
        }

        return response("Profile didn't exists", 404);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function store(Request $request)
    {
        $profile = InvoiceProfile::create([
            'user_id' => $request->user()->id,
            'logo' => store_system_image($request, 'logo') ?? null,
            'stamp' => store_system_image($request, 'stamp') ?? null,
            'company' => $request->company,
            'email' => $request->email,
            'ico' => $request->ico,
            'dic' => $request->dic,
            'ic_dph' => $request->ic_dph,
            'registration_notes' => $request->registration_notes,
            'author' => $request->author,
            'address' => $request->address,
            'state' => $request->state,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'phone' => $request->phone,
            'bank' => $request->bank,
            'iban' => $request->iban,
            'swift' => $request->swift,
        ]);

        return response(
            new InvoiceProfileResource($profile),
            201
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Store image if exist
        if ($request->hasFile($request->name)) {
            // Find and update image path
            $request->user()
                ->invoiceProfile()
                ->update([
                    $request->name => store_system_image($request, $request->name),
                ]);

            return response('Done', 204);
        }

        $request->user()
            ->invoiceProfile()
            ->update(make_single_input($request));

        return response('Done', 204);
    }
}
