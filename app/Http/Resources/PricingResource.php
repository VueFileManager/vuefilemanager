<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Cashier\Cashier;

class PricingResource extends JsonResource
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
                    'name'               => $this['product']['name'],
                    'description'        => $this['product']['description'],
                    'price'              => Cashier::formatAmount($this['plan']['amount']),
                    'capacity_formatted' => format_gigabytes($this['product']['metadata']['capacity']),
                    'capacity'           => (int)$this['product']['metadata']['capacity'],
                    'currency'           => config('cashier.currency'),
                    'tax_rates'          => $this->get_tax_rates(),
                ]
            ]
        ];
    }

    /**
     * Get plan tax rates
     *
     * @return array
     */
    private function get_tax_rates(): array
    {
        $stripe = resolve('App\Services\StripeService');

        $rates_puplic = [];

        // Get tax rates
        $rates = $stripe->getTaxRates();

        foreach ($rates as $rate) {

            // Continue when is not active
            if (!$rate['active']) continue;

            // Calculate tax
            $tax = $this['plan']['amount'] * ($rate['percentage'] / 100);

            array_push($rates_puplic, [
                'id'                   => $rate['id'],
                'active'               => $rate['active'],
                'jurisdiction'         => $rate['jurisdiction'],
                'percentage'           => $rate['percentage'],
                'plan_price_formatted' => Cashier::formatAmount(round($this['plan']['amount'] + $tax)),
            ]);
        }

        return $rates_puplic;
    }
}
