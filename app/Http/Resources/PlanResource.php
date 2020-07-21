<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription;

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
        // Get subscribers
        $subscriber_count = Subscription::where('stripe_plan', $this['plan']['id'])->where('stripe_status', 'active')->get();

        return [
            'data' => [
                'id'         => $this['plan']['id'],
                'type'       => 'plans',
                'attributes' => [
                    'subscribers'          => $subscriber_count->count(),
                    'status'               => $this['plan']['active'] ? 1 : 0,
                    'name'                 => $this['product']['name'],
                    'description'          => $this['product']['description'],
                    'price'                => $this['plan']['amount'],
                    'price_formatted'      => Cashier::formatAmount($this['plan']['amount']),
                    'capacity_formatted'   => format_gigabytes($this['product']['metadata']['capacity']),
                    'capacity'             => (int) $this['product']['metadata']['capacity'],
                    'created_at_formatted' => format_date($this['plan']['created']),
                    'created_at'           => $this['plan']['created'],
                ]
            ]
        ];
    }
}
