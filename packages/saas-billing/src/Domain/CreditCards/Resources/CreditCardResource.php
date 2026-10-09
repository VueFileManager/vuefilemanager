<?php
namespace VueFileManager\Subscription\Domain\CreditCards\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CreditCardResource extends JsonResource
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
                'type'       => 'credit-card',
                'attributes' => [
                    'brand'              => $this->brand,
                    'last4'              => $this->last4,
                    'service'            => $this->service,
                    'reference'          => $this->reference,
                    'isExpired'          => $this->is_expired,
                    'isBeforeExpiration' => $this->is_before_expiration,
                    'expiration'         => $this->expiration->formatLocalized('%b %Y'),
                    'created_at'         => $this->created_at->formatLocalized('%d. %b. %Y'),
                    'updated_at'         => $this->updated_at->formatLocalized('%d. %b. %Y'),
                ],
            ],
        ];
    }
}
