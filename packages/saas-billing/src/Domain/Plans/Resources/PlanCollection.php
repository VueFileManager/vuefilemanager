<?php
namespace VueFileManager\Subscription\Domain\Plans\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanCollection extends ResourceCollection
{
    public $collects = PlanResource::class;

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
