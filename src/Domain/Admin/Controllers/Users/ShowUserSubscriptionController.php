<?php
namespace Domain\Admin\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Users\Resources\UserSubscription;

class ShowUserSubscriptionController extends Controller
{
    /**
     * Get user subscription details
     */
    public function __invoke(User $user): UserSubscription|Response
    {
        if (! $user->stripeId() || ! $user->subscription('main')) {
            return response("User doesn't have any subscription.", 404);
        }

        return new UserSubscription($user);
    }
}
