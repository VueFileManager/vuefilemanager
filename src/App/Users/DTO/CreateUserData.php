<?php
namespace App\Users\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class CreateUserData extends DataTransferObject
{
    public string $role;
    public string $name;
    public string $email;
    public ?string $password;
    public ?string $oauth_provider;
    public ?string $avatar;

    public static function fromRequest($request): self
    {
        return new self([
            'role'            => $request->has('role') ? $request->input('role') : 'user',
            'name'            => $request->input('name'),
            'email'           => $request->input('email'),
            'avatar'          => $request->input('avatar') ?? null,
            'password'        => $request->input('password'),
            'oauth_provider'  => $request->input('oauth_provider') ?? null,
        ]);
    }

    public static function fromArray(array $array): self
    {
        return new self([
            'role'            => $array['role'] ?? 'user',
            'name'            => $array['name'] ?? null,
            'email'           => $array['email'],
            'avatar'          => $array['avatar'] ?? null,
            'password'        => $array['password'] ?? null,
            'oauth_provider'  => $array['oauth_provider'] ?? null,
        ]);
    }
}
