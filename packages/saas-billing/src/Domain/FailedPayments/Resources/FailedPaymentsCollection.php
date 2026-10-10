<?php
namespace VueFileManager\Subscription\Domain\FailedPayments\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FailedPaymentsCollection extends ResourceCollection
{
    public $collects = FailedPaymentResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
