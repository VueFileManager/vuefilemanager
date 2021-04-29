<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\JsonResource;

class OasisViewClientResource extends JsonResource
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
            'id'         => $this->id,
            'type'       => 'client',
            'created_at' => format_date($this->created_at, '%d. %B %Y'),

            'totalNet'      => format_to_currency($total_net, 'CZK'),
            'totalInvoices' => $total_invoices,

            'avatar'       => $this->avatar,
            'name'         => $this->name,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'address'      => $this->address,
            'city'         => $this->city,
            'postal_code'  => $this->postal_code,
            'country'      => $this->country,
            'ico'          => $this->ico,
            'dic'          => $this->dic,
            'ic_dph'       => $this->ic_dph,
        ];
    }
}
