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
                'id' => $this->id,
                'type' => 'invoice',
                'attributes' => [
                    'name' => $this->client['name'] . ' ' . format_to_currency($this->total_net, $this->currency),
                    'invoiceNumber' => $this->invoice_number,
                    'total' => format_to_currency($this->total_net, $this->currency),
                    'file_url' => "/oasis/invoice/$this->id",
                    'mimetype' => 'pdf',
                    'created_at' => format_date($this->created_at, '%d. %B. %Y'),
                ],
            ],
        ];
    }
}
