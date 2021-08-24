<?php
namespace Domain\Teams\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class CreateTeamFolderData extends DataTransferObject
{
    public string $name;
    public array $members;

    public static function fromRequest($request): self
    {
        return new self([
            'name'    => $request->input('name'),
            'members' => $request->input('members'),
        ]);
    }
}
