<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Cashier\Cashier;

class InvoiceProfileResource extends JsonResource
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
                'type'       => 'invoice-profile',
                'attributes' => [
                    'logo'               => $this->logo,
                    'stamp'              => $this->stamp,
                    'company'            => $this->company,
                    'email'              => $this->email,
                    'ico'                => $this->ico,
                    'dic'                => $this->dic,
                    'ic_dph'             => $this->ic_dph,
                    'registration_notes' => $this->registration_notes,
                    'author'             => $this->author,
                    'address'            => $this->address,
                    'state'              => $this->state,
                    'city'               => $this->city,
                    'postal_code'        => $this->postal_code,
                    'country'            => $this->country,
                    'phone'              => $this->phone,
                    'bank'               => $this->bank,
                    'iban'               => $this->iban,
                    'swift'              => $this->swift,
                ],
            ],
        ];
    }
}
