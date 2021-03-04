<?php

namespace App\Http\Resources;

use App\Models\User;
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
        $user = User::whereStripeId($this->customer)
            ->first();

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
                    'user_id'               => $user->id ?? null,
                    'client'                => [
                        'billing_address'      => $this->customer_address,
                        'billing_name'         => $this->customer_name,
                        'billing_phone_number' => $this->customer_phone,
                    ],
                    'seller'                => null,
                    'invoice_items'         => $this->get_invoice_items(),
                    'invoice_subscriptions' => $this->get_invoice_subscriptions(),
                ],
                $this->mergeWhen($user, [
                    'relationships' => [
                        'user' => [
                            'data' => [
                                'id'         => $user->id,
                                'type'       => 'user',
                                'attributes' => [
                                    'name'   => $user->settings->name,
                                    'avatar' => $user->settings->avatar,
                                ]
                            ]
                        ]
                    ]
                ]),
            ],
        ];
    }

    /**
     * @return array
     */
    private function get_invoice_subscriptions(): array
    {
        $array = [];

        foreach ($this->subscriptions() as $item) {
            array_push($array, [
                'amount'      => $item->total(),
                'description' => $item->description,
                'currency'    => $item->currency,
                'type'        => $item->type,
            ]);
        }

        return $array;
    }

    /**
     * @return array
     */
    private function get_invoice_items(): array
    {
        $array = [];

        foreach ($this->invoiceItems() as $item) {
            array_push($array, [
                'amount'      => $item->total(),
                'description' => $item->description,
                'currency'    => $item->currency,
                'type'        => $item->type,
            ]);
        }

        return $array;
    }
}
