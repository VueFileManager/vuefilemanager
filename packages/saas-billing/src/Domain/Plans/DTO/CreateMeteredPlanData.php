<?php
namespace VueFileManager\Subscription\Domain\Plans\DTO;

use Illuminate\Support\Collection;

final readonly class CreateMeteredPlanData
{
    public function __construct(
        public array|Collection $meters,
        public string $currency,
        public string $name,
        public string $type,
        public ?string $description = null,
    ) {
    }

    public static function fromRequest($request): self
    {
        return new self(
            meters: $request->input('meters'),
            currency: $request->input('currency'),
            name: $request->input('name'),
            type: $request->input('type'),
            description: $request->input('description'),
        );
    }

    public static function fromArray(array $array): self
    {
        return new self(
            meters: $array['meters'],
            currency: $array['currency'],
            name: $array['name'],
            type: $array['type'],
            description: $array['description'] ?? null,
        );
    }
}
