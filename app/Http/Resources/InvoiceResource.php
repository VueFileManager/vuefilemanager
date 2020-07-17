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
        $invoice_items = [];
        $invoice_subscriptions = [];

        // Format bag
        foreach ($this->invoiceItems() as $item) {
            array_push($invoice_items, [
                'amount'      => $item->total(),
                'description' => $item->description,
                'currency'    => $item->currency,
                'type'        => $item->type,
            ]);
        }

        // Format bag
        foreach ($this->subscriptions() as $item) {
            array_push($invoice_subscriptions, [
                'amount'      => $item->total(),
                'description' => $item->description,
                'currency'    => $item->currency,
                'type'        => $item->type,
            ]);
        }

        return [
            'data' => [
                'id'         => $this->id,
                'type'       => 'invoices',
                'attributes' => [
                    'customer'              => $this->customer,
                    'total'                 => $this->total(),
                    'currency'              => $this->currency,
                    'created_at_formatted'  => format_date($this->date(), '%d. %B. %Y'),
                    'created_at'            => $this->created,
                    'order'                 => $this->number,
                    'user_id'               => $user ? $user->id : null,
                    'client'                => [
                        'billing_address'      => $this->customer_address,
                        'billing_name'         => $this->customer_name,
                        'billing_phone_number' => $this->customer_phone,
                    ],
                    'seller'                => null,
                    'invoice_items'         => $invoice_items,
                    'invoice_subscriptions' => $invoice_subscriptions,
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
