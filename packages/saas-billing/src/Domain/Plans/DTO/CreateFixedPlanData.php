<?php
namespace VueFileManager\Subscription\Domain\Plans\DTO;

use Illuminate\Support\Collection;

final readonly class CreateFixedPlanData
{
    public function __construct(
        public string $type,
        public string $name,
        public float $amount,
        public string $currency,
        public string $interval,
        public array|Collection $features,
        public ?string $description = null,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            type: $request->input('type'),
            name: $request->input('name'),
            amount: $request->input('amount'),
            currency: $request->input('currency'),
            interval: $request->input('interval'),
            features: $request->input('features'),
            description: $request->input('description'),
        );
    }

    public static function fromArray(array $array): self
    {
        return new self(
            type: $array['type'],
            name: $array['name'],
            amount: $array['amount'],
            currency: $array['currency'],
            interval: $array['interval'],
            features: $array['features'],
            description: $array['description'] ?? null,
        );
    }
}
