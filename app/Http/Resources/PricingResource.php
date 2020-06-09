<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'id'         => (string)$this->id,
                'type'       => 'plans',
                'attributes' => [
                    'name'                 => $this->name,
                    'description'          => $this->description,
                    'price'                => $this->price,
                    'capacity_formatted'   => format_gigabytes($this->features->first()->value),
                    'capacity'             => (int) $this->features->first()->value,
                    'currency'             => 'USD',
                ]
            ]
        ];
    }
}
