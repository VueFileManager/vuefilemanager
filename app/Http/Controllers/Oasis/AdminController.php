<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\Oasis\PaymentRequiredNotification;
use App\Services\Oasis\CzechRegisterSearchService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Notification;

class AdminController extends Controller
{
    /**
     * Get company details from czech company register
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get_company_details()
    {
        $api = resolve(CzechRegisterSearchService::class);

        $result = $api->findByIco(
            request()->get('ico')
        );

        if (empty($result)) {
            return response('Not Found', 404);
        }

        return response($result[0], 200);
    }

    public function register_new_client(Request $request)
    {
        $newbie = User::create([
            'email'    => $request->email,
            'password' => Hash::make(Str::random()),
        ]);

        $newbie
            ->settings()
            ->create([
                'ico'            => $request->ico,
                'name'           => $request->name,
                'address'        => $request->address,
                'state'          => $request->state,
                'city'           => $request->city,
                'postal_code'    => $request->postal_code,
                'country'        => $request->country,
                'phone_number'   => $request->phone_number,
                'timezone'       => '1.0',
                'requested_plan' => $request->plan,
            ]);

        $newbie->notify(new PaymentRequiredNotification());

        return response(
            new UserResource($newbie), 201
        );
    }
}
