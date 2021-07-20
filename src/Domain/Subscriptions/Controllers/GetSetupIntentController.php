<?php


namespace Domain\Subscriptions\Controllers;


use App\Http\Controllers\Controller;
use Auth;
use Domain\Subscriptions\Services\StripeService;
use Illuminate\Http\Response;

/**
 * Generate setup intent
 */
class GetSetupIntentController extends Controller
{
    public function __construct(
        public StripeService $stripe,
    ) {}

    public function setup_intent(): Response
    {
        return response(
            $this->stripe->getSetupIntent(Auth::user()), 201
        );
    }
}