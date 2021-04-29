<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OasisViewClientCollection extends ResourceCollection
{
    public $collects = OasisViewClientResource::class;

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
