<?php

namespace App\Http\Resources\Oasis;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OasisClientCollection extends ResourceCollection
{
    public $collects = OasisClientResource::class;

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
