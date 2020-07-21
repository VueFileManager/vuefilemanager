<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Cashier\Cashier;

class InvoiceAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('stripe_id', $this['customer'])->first();

        return [
            'data' => [
                'id'         => $this['id'],
                'type'       => 'invoices',
                'attributes' => [
                    'customer'             => $this['customer'],
                    'total'                => Cashier::formatAmount($this['total']),
                    'currency'             => $this['currency'],
                    'created_at_formatted' => format_date($this['created']),
                    'created_at'           => $this['created'],
                    'order'                => $this['number'],
                    'user_id'              => $user ? $user->id : null,
                    'client'               => [
                        'billing_address'      => $this['customer_address'],
                        'billing_name'         => $this['customer_name'],
                        'billing_phone_number' => $this['customer_phone'],
                    ],
                    'bag'                  => [
                        'amount'      => $this['lines']['data'][0]['amount'],
                        'currency'    => $this['lines']['data'][0]['currency'],
                        'type'        => $this['lines']['data'][0]['type'],
                        'description' => $this['lines']['data'][0]['description'],
                    ],
                    'seller'               => null,
                ]
            ],
            $this->mergeWhen($user, function () use ($user) {
                return [
                    'relationships' => [
                        'user' => [
                            'data' => [
                                'id'         => (string)$user->id,
                                'type'       => 'user',
                                'attributes' => [
                                    'name'            => $user->name,
                                    'avatar'          => $user->avatar,
                                ]
                            ]
                        ]
                    ]
                ];
            }),
        ];
    }
}
