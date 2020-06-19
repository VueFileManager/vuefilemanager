<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
                'id'         => $this['plan']['id'],
                'type'       => 'plans',
                'attributes' => [
                    'subscribers'          => $this['plan']['aggregate_usage'],
                    'status'               => $this['plan']['active'],
                    'name'                 => $this['product']['name'],
                    'description'          => $this['product']['description'],
                    'price'                => $this['plan']['amount'],
                    'capacity_formatted'   => format_gigabytes($this['product']['metadata']['capacity']),
                    'capacity'             => $this['product']['metadata']['capacity'],
                    'created_at_formatted' => format_date($this['plan']['created']),
                    'created_at'           => $this['plan']['created'],
                ]
            ]
        ];
    }
}
