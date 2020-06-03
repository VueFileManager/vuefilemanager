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
                'id'         => (string)$this->id,
                'type'       => 'plans',
                'attributes' => [
                    'status'               => $this->status,
                    'name'                 => $this->name,
                    'description'          => $this->description,
                    'price'                => $this->price,
                    'capacity_formatted'   => format_gigabytes($this->capacity),
                    'capacity'             => $this->capacity,
                    'created_at_formatted' => format_date($this->created_at),
                    'created_at'           => $this->created_at,
                    'updated_at'           => $this->updated_at,
                ]
            ]
        ];
    }
}
