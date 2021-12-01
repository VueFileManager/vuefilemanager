<?php
namespace App\Users\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersMinimalCollection extends ResourceCollection
{
    public $collects = UserMinimalResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
