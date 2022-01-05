<?php
namespace App\Limitations;

use Illuminate\Support\Manager;
use App\Limitations\Engines\DefaultLimitationEngine;
use App\Limitations\Engines\FixedBillingLimitationEngine;
use App\Limitations\Engines\MeteredBillingLimitationEngine;

class LimitationManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return get_limitation_driver();
    }

    public function createDefaultDriver(): DefaultLimitationEngine
    {
        return new DefaultLimitationEngine();
    }

    public function createFixedDriver(): FixedBillingLimitationEngine
    {
        return new FixedBillingLimitationEngine();
    }

    public function createMeteredDriver(): MeteredBillingLimitationEngine
    {
        return new MeteredBillingLimitationEngine();
    }
}
