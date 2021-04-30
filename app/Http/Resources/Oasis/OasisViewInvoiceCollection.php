<?php
namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OasisViewInvoiceCollection extends ResourceCollection
{
    public $collects = OasisViewInvoiceResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
