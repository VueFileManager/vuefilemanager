<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\Oasis\PaymentRequiredNotification;
use App\Services\Oasis\CzechRegisterSearchService;
use App\Services\StripeService;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Notification;

class AdminController extends Controller
{
    /**
     * Get company details from czech company register
     *
     * @return array|Application|ResponseFactory|Response
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

    /**
     * Register new client and send email with payment details
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function register_new_client(Request $request)
    {
        // Create user
        $newbie = User::create([
            'email'    => $request->email,
            'password' => Hash::make(Str::random()),
        ]);

        // Store user settings
        $newbie
            ->settings()
            ->create([
                'ico'          => $request->ico,
                'name'         => $request->name,
                'address'      => $request->address,
                'state'        => $request->state,
                'city'         => $request->city,
                'postal_code'  => $request->postal_code,
                'country'      => $request->country,
                'phone_number' => $request->phone_number,
                'timezone'     => '1.0',
            ]);

        // Store subscription request
        $newbie
            ->subscriptionRequest()
            ->create([
                'requested_plan' => $request->plan,
            ]);

        $plan = resolve(StripeService::class)
            ->getPlan($request->plan);

        // Send notification with payment details
        $newbie->notify(new PaymentRequiredNotification(
            $newbie->subscriptionRequest,
            $plan
        ));

        return response(
            new UserResource($newbie), 201
        );
    }
}
