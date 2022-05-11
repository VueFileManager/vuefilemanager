<?php
namespace App\Users\Resources;

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
        $active_subscription = $this->subscription('main')
            ->asStripeSubscription();

        // TODO: vybrat z cache
        $subscription = resolve('App\Services\StripeService')
            ->getPlan($this->subscription('main')->stripe_plan);

        return [
            'data' => [
                'id'         => $subscription['plan']['id'],
                'type'       => 'subscription',
                'attributes' => [
                    'incomplete'         => $this->subscription('main')->incomplete(),
                    'active'             => $this->subscription('main')->active(),
                    'canceled'           => $this->subscription('main')->cancelled(),
                    'name'               => $subscription['product']['name'],
                    'capacity'           => (int) $subscription['product']['metadata']['capacity'],
                    'capacity_formatted' => toGigabytes($subscription['product']['metadata']['capacity']),
                    'slug'               => $subscription['plan']['id'],
                    'canceled_at'        => format_date($active_subscription['canceled_at'], 'd. M. Y'),
                    'created_at'         => format_date($active_subscription['current_period_start'], 'd. M. Y'),
                    'ends_at'            => format_date($active_subscription['current_period_end'], 'd. M. Y'),
                ],
            ],
        ];
    }
}
