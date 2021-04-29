<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\JsonResource;

class OasisClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $total_net = \DB::table('invoices')
            ->whereClientId($this->id)
            ->sum('total_net');

        $total_invoices = \DB::table('invoices')
            ->whereClientId($this->id)
            ->count();

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'avatar'        => $this->avatar,
            'totalNet'      => format_to_currency($total_net, 'CZK'),
            'totalInvoices' => $total_invoices,
            'type'          => 'client',
            'created_at'    => format_date($this->created_at, '%d. %B %Y'),
        ];
    }
}
