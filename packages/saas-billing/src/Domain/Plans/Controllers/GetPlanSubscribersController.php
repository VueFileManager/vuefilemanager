<?php
namespace VueFileManager\Subscription\Domain\Plans\Controllers;

use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionCollection;

class GetPlanSubscribersController extends Controller
{
    public function __invoke(Plan $plan): SubscriptionCollection
    {
        return new SubscriptionCollection(
            $plan->subscriptions()
                ->sortable(['created_at' => 'desc'])
                ->paginate(20)
        );
    }
}
