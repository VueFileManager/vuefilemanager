<?php
namespace Domain\Teams\DTO;

final readonly class CreateTeamFolderData
{
    public function __construct(
        public string $name,
        public array $invitations,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->input('name'),
            invitations: $request->input('invitations'),
        );
    }
}
