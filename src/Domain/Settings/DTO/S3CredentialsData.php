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
            'key'      => $request->input('storage.s3.key'),
            'secret'   => $request->input('storage.s3.secret'),
            'region'   => $request->input('storage.s3.region'),
            'bucket'   => $request->input('storage.s3.bucket'),
            'endpoint' => $request->input('storage.s3.endpoint'),
        ]);
    }
}
