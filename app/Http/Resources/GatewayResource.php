<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
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
                'type'       => 'gateways',
                'attributes' => [
                    'status'             => $this->status,
                    'sandbox'            => $this->sandbox,
                    'name'               => $this->name,
                    'slug'               => $this->slug,
                    'logo'               => $this->logo,
                    'client_id'          => $this->client_id,
                    'secret'             => $this->secret,
                    'webhook'            => $this->webhook,
                    'payment_processed' => $this->payment_processed,
                    'optional'           => $this->optional,
                ]
            ]
        ];
    }
}
