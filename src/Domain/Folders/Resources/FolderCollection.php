<?php
namespace Domain\Folders\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FolderCollection extends ResourceCollection
{
    public $collects = FolderResource::class;

    public function toArray($request): Collection
    {
        return $this->collection;
    }
}
