<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id'         => (string)$this['id'],
                'type'       => 'payment_method',
                'attributes' => [
                    'provider'   => 'stripe',
                    'card_id'    => $this['id'],
                    'brand'      => strtolower($this['card']['brand']),
                    'last4'      => $this['card']['last4'],
                    'exp_month'  => $this['card']['exp_month'],
                    'exp_year'   => $this['card']['exp_year'],
                    'created_at' => format_date($this['created_at'], '%d. %B. %Y'),
                    'status'     => 'active',
                    'default'    => 0,
                ]
            ]
        ];
    }
}
