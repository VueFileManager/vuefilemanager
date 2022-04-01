<?php
namespace Domain\Settings\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class S3CredentialsData extends DataTransferObject
{
    public string $key;
    public string $secret;
    public string $region;
    public string $bucket;
    public string $endpoint;

    public static function fromRequest($request): self
    {
        return new self([
            'key'      => $request->input('storage.key'),
            'secret'   => $request->input('storage.secret'),
            'region'   => $request->input('storage.region'),
            'bucket'   => $request->input('storage.bucket'),
            'endpoint' => $request->input('storage.endpoint'),
        ]);
    }
}
