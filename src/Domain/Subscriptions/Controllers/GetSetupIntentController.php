<?php
namespace Domain\Subscriptions\Controllers;

use Auth;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Subscriptions\Services\StripeService;

/**
 * Generate setup intent
 */
class GetSetupIntentController extends Controller
{
    public function __construct(
        public StripeService $stripe,
    ) {
    }

    public function __invoke(): Response
    {
        return response(
            $this->stripe->getSetupIntent(Auth::user()),
            201
        );
    }
}
