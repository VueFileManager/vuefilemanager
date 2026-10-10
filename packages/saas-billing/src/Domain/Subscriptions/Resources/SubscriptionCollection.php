<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SubscriptionCollection extends ResourceCollection
{
    public $collects = SubscriptionResource::class;

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
