<?php
namespace App\Users\Restrictions;

use Illuminate\Support\Manager;
use App\Users\Restrictions\Engines\DefaultRestrictionsEngine;
use App\Users\Restrictions\Engines\FixedBillingRestrictionsEngine;
use App\Users\Restrictions\Engines\MeteredBillingRestrictionsEngine;

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
