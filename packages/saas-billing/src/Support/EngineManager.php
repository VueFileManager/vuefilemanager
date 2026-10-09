<?php
namespace VueFileManager\Subscription\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Manager;
use VueFileManager\Subscription\Support\Engines\Engine;
use VueFileManager\Subscription\Support\Engines\PayPalEngine;
use VueFileManager\Subscription\Support\Engines\StripeEngine;
use VueFileManager\Subscription\Support\Engines\PayStackEngine;
use VueFileManager\Subscription\Domain\Plans\DTO\CreateFixedPlanData;

/**
 * @method createFixedPlan(CreateFixedPlanData $data)
 * @method createCustomer(array $user)
 * @method webhook(Request $request)
 */
class EngineManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'stripe';
    }

    public function createStripeDriver(): Engine
    {
        return new StripeEngine();
    }

    public function createPayStackDriver(): Engine
    {
        return new PayStackEngine();
    }

    public function createPayPalDriver(): Engine
    {
        return new PayPalEngine();
    }
}
