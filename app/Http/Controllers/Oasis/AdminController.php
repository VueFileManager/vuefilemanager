<?php

namespace App\Http\Controllers\Oasis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserByAdmin;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserSettings;
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
    public function create_order(Request $request)
    {
        // Create user
        $newbie = User::create([
            'email'    => $request->email,
            'password' => Hash::make(Str::random()),
        ]);

        UserSettings::unguard();

        // Store user settings
        $newbie
            ->settings()
            ->create([
                'storage_capacity' => 0,
                'ico'              => $request->ico,
                'name'             => $request->name,
                'address'          => $request->address,
                'state'            => $request->state,
                'city'             => $request->city,
                'postal_code'      => $request->postal_code,
                'country'          => $request->country,
                'phone_number'     => $request->phone_number,
                'timezone'         => '1.0',
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

    /**
     * Create new user by admin
     *
     * @param CreateUserByAdmin $request
     * @return UserResource|Application|ResponseFactory|Response
     */
    public function create_user(CreateUserByAdmin $request)
    {
        // Create user
        $user = User::forceCreate([
            'role'     => $request->role,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        UserSettings::unguard();

        $user
            ->settings()
            ->create([
                'name'               => $request->name,
                'avatar'             => store_avatar($request, 'avatar'),
                'storage_capacity'   => $request->storage_capacity,
                'payment_activation' => 1,
            ]);

        return response(new UserResource($user), 201);
    }
}
