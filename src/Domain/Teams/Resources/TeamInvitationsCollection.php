<?php
namespace Domain\Teams\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TeamInvitationsCollection extends ResourceCollection
{
    public $collects = TeamInvitationResource::class;

    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
