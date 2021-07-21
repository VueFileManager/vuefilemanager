<?php
namespace Domain\Plans\Controllers;

use App\Users\Models\User;
use Laravel\Cashier\Subscription;
use App\Http\Controllers\Controller;
use App\Users\Resources\UsersCollection;

class PlanSubscribersController extends Controller
{
    /**
     * Get plan subscriptions
     */
    public function __invoke(string $id): UsersCollection
    {
        $subscribers = Subscription::whereStripePlan($id)
            ->pluck('user_id');

        $users = User::sortable()
            ->findMany($subscribers);

        return new UsersCollection($users);
    }
}
