<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscription extends JsonResource
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
                'type'       => 'subscription',
                'attributes' => [
                    'active'             => $this->active(),
                    'canceled'           => $this->canceled(),
                    'name'               => $this->plan->name,
                    'capacity'           => (int) $this->plan->features->first()->value,
                    'capacity_formatted' => format_gigabytes($this->plan->features->first()->value),
                    'slug'               => $this->slug,
                    'canceled_at'        => format_date($this->created_at, '%d. %B. %Y'),
                    'created_at'         => format_date($this->created_at, '%d. %B. %Y'),
                    'starts_at'          => format_date($this->starts_at, '%d. %B. %Y'),
                    'ends_at'            => format_date($this->ends_at, '%d. %B. %Y'),
                ]
            ]
        ];
    }
}
