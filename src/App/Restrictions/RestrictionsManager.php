<?php
namespace App\Restrictions;

use Illuminate\Support\Manager;
use App\Restrictions\Engines\DefaultRestrictionsEngine;
use App\Restrictions\Engines\FixedBillingRestrictionsEngine;
use App\Restrictions\Engines\MeteredBillingRestrictionsEngine;

class RestrictionsManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return get_restriction_driver();
    }

    public function createDefaultDriver(): DefaultRestrictionsEngine
    {
        return new DefaultRestrictionsEngine();
    }

    public function createFixedDriver(): FixedBillingRestrictionsEngine
    {
        return new FixedBillingRestrictionsEngine();
    }

    public function createMeteredDriver(): MeteredBillingRestrictionsEngine
    {
        return new MeteredBillingRestrictionsEngine();
    }
}
