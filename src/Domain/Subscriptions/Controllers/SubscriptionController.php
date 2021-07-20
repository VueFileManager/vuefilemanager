<?php
namespace Domain\Subscriptions\Controllers;

use Auth;
use Stripe\SetupIntent;
use App\Users\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Support\Demo\Actions\DemoService;
use App\Users\Resources\UserSubscription;
use Domain\Subscriptions\Services\StripeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\Subscriptions\Requests\StoreUpgradeAccountRequest;

class SubscriptionController extends Controller
{
    public function __construct(
        private StripeService $stripe,
        private DemoService $demo,
    ) {
    }

    /**
     * Get user subscription detail
     *
     * @return void
     */
    public function show()
    {
        $user = User::find(Auth::id());

        if (! $user->subscription('main')) {
            return abort(204, 'User don\'t have any subscription');
        }

        $slug = 'subscription-user-' . $user->id;

        if (Cache::has($slug)) {
            return Cache::get($slug);
        }

        return Cache::rememberForever($slug, function () use ($user) {
            return new UserSubscription(
                $user
            );
        });
    }
}
