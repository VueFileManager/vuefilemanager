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
        $stripe = resolve('App\Services\StripeService');

        $active_subscription = $this->subscription('main')->asStripeSubscription();

        // Get subscription details
        $subscription = $stripe->getPlan($this->subscription('main')->stripe_plan);

        // Retrieve the timestamp from Stripe
        $current_period_end = $active_subscription["current_period_end"];
        $current_period_start = $active_subscription["current_period_start"];
        $canceled_at = $active_subscription["canceled_at"];

        return [
            'data' => [
                'id'         => $subscription['plan']['id'],
                'type'       => 'subscription',
                'attributes' => [
                    'incomplete'         => $this->subscription('main')->incomplete(),
                    'active'             => $this->subscription('main')->active(),
                    'canceled'           => $this->subscription('main')->cancelled(),
                    'name'               => $subscription['product']['name'],
                    'capacity'           => (int)$subscription['product']['metadata']['capacity'],
                    'capacity_formatted' => format_gigabytes($subscription['product']['metadata']['capacity']),
                    'slug'               => $subscription['plan']['id'],
                    'canceled_at'        => format_date($canceled_at, '%d. %B. %Y'),
                    'created_at'         => format_date($current_period_start, '%d. %B. %Y'),
                    'ends_at'            => format_date($current_period_end, '%d. %B. %Y'),
                ]
            ]
        ];
    }
}
