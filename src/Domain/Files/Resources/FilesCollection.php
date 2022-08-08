<?php
namespace Domain\Files\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FilesCollection extends ResourceCollection
{
    public $collects = FileResource::class;

    public function toArray($request): Collection
    {
        return $this->collection;
    }
}
