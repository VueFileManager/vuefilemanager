<?php

namespace App\Http\Resources;

use App\Services\StripeService;
use App\Models\User;
use Cartalyst\Stripe\Api\PaymentMethods;
use Faker\Factory;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // TODO: zrefaktorovat
        return [
            'data' => [
                'id'            => $this->id,
                'type'          => 'user',
                'attributes'    => [
                    'storage_capacity'     => $this->settings->storage_capacity,
                    'subscription'         => $this->subscribed('main'),
                    'incomplete_payment'   => $this->hasIncompletePayment('main') ? route('cashier.payment', $this->subscription('main')->latestPayment()->id) : null,
                    'stripe_customer'      => is_null($this->stripe_id) ? false : true,
                    'email'                => env('APP_DEMO') ? obfuscate_email($this->email) : $this->email,
                    'role'                 => $this->role,
                    'folders'              => $this->folder_tree,
                    'storage'              => $this->storage,
                    'created_at_formatted' => format_date($this->created_at, '%d. %B. %Y'),
                    'created_at'           => $this->created_at,
                    'updated_at'           => $this->updated_at,
                ],
                'relationships' => [
                    'settings'   => [
                        'data' => [
                            'id'         => $this->id,
                            'type'       => 'settings',
                            'attributes' => [
                                'avatar'       => $this->settings->avatar,
                                'name'         => $this->settings->name,
                                'address'      => $this->settings->address,
                                'state'        => $this->settings->state,
                                'city'         => $this->settings->city,
                                'postal_code'  => $this->settings->postal_code,
                                'country'      => $this->settings->country,
                                'phone_number' => $this->settings->phone_number,
                                'timezone'     => $this->settings->timezone
                            ]
                        ]
                    ],
                    'favourites' => [
                        'data' => [
                            'id'         => $this->id,
                            'type'       => 'favourite_folders',
                            'attributes' => [
                                'folders' => $this->favouriteFolders->makeHidden(['pivot'])
                            ],
                        ],
                    ]
                ]
            ],
        ];
    }
}
