<?php
namespace App\Users\DTO;

final readonly class CreateUserData
{
    public function __construct(
        public string $role,
        public string $name,
        public string $email,
        public ?string $password = null,
        public ?string $oauth_provider = null,
        public ?string $avatar = null,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            role: $request->has('role') ? $request->input('role') : 'user',
            name: $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
            oauth_provider: $request->input('oauth_provider') ?? null,
            avatar: $request->input('avatar') ?? null,
        );
    }

    public static function fromArray(array $array): self
    {
        return new self(
            role: $array['role'] ?? 'user',
            name: $array['name'] ?? null,
            email: $array['email'],
            password: $array['password'] ?? null,
            oauth_provider: $array['oauth_provider'] ?? null,
            avatar: $array['avatar'] ?? null,
        );
    }
}
