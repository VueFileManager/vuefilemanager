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
        return 'default';
    }

    public function createDefaultDriver(): DefaultRestrictionsEngine
    {
        return new DefaultRestrictionsEngine();
    }
}
