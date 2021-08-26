<?php
namespace Domain\Files\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FilesCollection extends ResourceCollection
{
    public $collects = FileResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
