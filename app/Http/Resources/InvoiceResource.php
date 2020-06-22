<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('stripe_id', $this->customer)->first();
        $subscription = $this->subscriptions()[0];

        return [
            'data' => [
                'id'         => $this->id,
                'type'       => 'invoices',
                'attributes' => [
                    'customer'             => $this->customer,
                    'total'                => $this->total(),
                    'currency'             => $this->currency,
                    'created_at_formatted' => format_date($this->date()),
                    'created_at'           => $this->created,
                    'order'                => $this->number,
                    'user_id'              => $user ? $user->id : null,
                    'client'               => [
                        'billing_address'      => $this->customer_address,
                        'billing_name'         => $this->customer_name,
                        'billing_phone_number' => $this->customer_phone,
                    ],
                    'bag'                  => [
                        'amount'      => $subscription->amount,
                        'currency'    => $subscription->currency,
                        'type'        => $subscription->type,
                        'description' => $subscription->description,
                    ],
                    'seller'               => null,
                ]
            ],
            $this->mergeWhen($user, [
                'relationships' => [
                    'user' => [
                        'data' => [
                            'id'         => (string)$user->id,
                            'type'       => 'user',
                            'attributes' => [
                                'name'   => $user->name,
                                'avatar' => $user->avatar,
                            ]
                        ]
                    ]
                ]
            ]),
        ];
    }
}
