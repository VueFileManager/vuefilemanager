<?php

namespace App\Http\Resources;

use App\Services\StripeService;
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
                    'tax_rates'          => resolve(StripeService::class)->get_tax_rates($this['plan']['amount'])
                ]
            ]
        ];
    }
}