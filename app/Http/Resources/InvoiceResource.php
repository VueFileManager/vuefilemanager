<?php

namespace App\Http\Resources;

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
        return [
            'data'          => [
                'id'         => (string)$this->id,
                'type'       => 'invoices',
                'attributes' => [
                    'token'                => $this->token,
                    'order'                => $this->order,
                    'user_id'              => $this->user_id,
                    'plan_id'              => $this->plan_id,
                    'notes'                => $this->notes,
                    'total'                => $this->total,
                    'currency'             => $this->currency,
                    'seller'               => $this->seller,
                    'client'               => $this->client,
                    'bag'                  => $this->bag,
                    'created_at_formatted' => format_date($this->created_at),
                    'created_at'           => $this->created_at,
                    'updated_at'           => $this->updated_at,
                ]
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'id'         => (string)$this->user->id,
                        'type'       => 'user',
                        'attributes' => [
                            'name'   => $this->user->name,
                            'avatar' => $this->user->avatar,
                        ]
                    ]
                ]
            ]
        ];
    }
}
