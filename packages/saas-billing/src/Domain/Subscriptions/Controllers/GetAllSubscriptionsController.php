<?php
namespace VueFileManager\Subscription\Domain\Subscriptions\Controllers;

use Illuminate\Routing\Controller;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Subscriptions\Resources\SubscriptionCollection;

class GetAllSubscriptionsController extends Controller
{
    public function __invoke(): SubscriptionCollection
    {
        $subscriptions = Subscription::sortable(['created_at' => 'desc'])
            ->paginate(20);

        return new SubscriptionCollection($subscriptions);
    }
}
