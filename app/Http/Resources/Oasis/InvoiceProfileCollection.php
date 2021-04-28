<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InvoiceProfileCollection extends ResourceCollection
{
    public $collects = InvoiceProfileResource::class;

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
