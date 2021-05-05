<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\JsonResource;

class OasisInvoiceResource extends JsonResource
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
                'id'         => $this->id,
                'type'       => 'invoice',
                'attributes' => [
                    'name'            => $this->client['name'] . ' ' . format_to_currency($this->total_net, $this->currency),
                    'invoice_number'  => $this->invoice_number,
                    'variable_number' => $this->variable_number,
                    'invoice_type'    => $this->invoice_type,
                    'delivery_at'     => $this->delivery_at,
                    'items'           => $this->items,
                    'discount_type'   => $this->discount_type,
                    'discount_rate'   => $this->discount_rate,
                    'client'          => $this->client,
                    'total'           => format_to_currency($this->total_net, $this->currency),
                    'file_url'        => "/oasis/invoice/$this->id",
                    'mimetype'        => 'pdf',
                    'created_at'      => format_date($this->created_at, '%d. %B. %Y'),
                ],
            ],
        ];
    }
}
