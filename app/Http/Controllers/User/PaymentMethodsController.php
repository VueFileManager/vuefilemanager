<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\RegisterNewPaymentMethodRequest;
use App\Http\Resources\PaymentCardCollection;
use App\Http\Resources\PaymentCardResource;
use App\Http\Resources\PaymentDefaultCardResource;
use App\Http\Tools\Demo;
use App\Services\StripeService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\PaymentMethod;

class PaymentMethodsController extends Controller
{
    /**
     * PaymentMethodsController constructor.
     */
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * Get user payment methods grouped by default and others
     *
     * @return array
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user->hasPaymentMethod()) {
            return abort(204, 'User don\'t have any payment methods');
        }

        $slug_payment_methods = 'payment-methods-user-' . $user->id;
        $slug_default_payment_method = 'default-payment-methods-user-' . $user->id;

        if (Cache::has($slug_payment_methods) && Cache::has($slug_default_payment_method)) {

            $defaultPaymentMethod = Cache::get($slug_default_payment_method);
            $paymentMethodsMapped = Cache::get($slug_payment_methods);

        } else {

            // Get default payment method
            $defaultPaymentMethod = Cache::rememberForever($slug_default_payment_method, function () use ($user) {

                $defaultPaymentMethodObject = $user->defaultPaymentMethod();

                return $defaultPaymentMethodObject instanceof PaymentMethod
                    ? $defaultPaymentMethodObject->asStripePaymentMethod()
                    : $defaultPaymentMethodObject;
            });

            // filter payment methods without default payment
            $paymentMethodsMapped = Cache::rememberForever($slug_payment_methods, function () use ($defaultPaymentMethod, $user) {

                $paymentMethods = $user->paymentMethods()->filter(function ($paymentMethod) use ($defaultPaymentMethod) {
                    return $paymentMethod->id !== $defaultPaymentMethod->id;
                });

                // Get payment methods
                return $paymentMethods->map(function ($paymentMethod) {
                    return $paymentMethod->asStripePaymentMethod();
                })->values()->all();
            });
        }

        if (!$user->card_brand || !$user->stripe_id || is_null($paymentMethodsMapped) && is_null($paymentMethodsMapped)) {
            return [
                'default' => null,
                'others'  => [],
            ];
        }

        return [
            'default' => $defaultPaymentMethod instanceof PaymentMethod
                ? new PaymentCardResource($defaultPaymentMethod)
                : new PaymentDefaultCardResource($defaultPaymentMethod),
            'others'  => new PaymentCardCollection($paymentMethodsMapped),
        ];
    }

    /**
     * Update default payment method
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update($id)
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Update DefaultPayment Method
        $user->updateDefaultPaymentMethod($id);

        // Sync default payment method
        $user->updateDefaultPaymentMethodFromStripe();

        // Clear cached payment methods
        cache_forget_many([
            'payment-methods-user-' . $user->id,
            'default-payment-methods-user-' . $user->id
        ]);

        return response('Done', 204);
    }

    /**
     * Register new payment method for user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(RegisterNewPaymentMethodRequest $request)
    {
        // Get user
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return response('Done', 201);
        }

        // Register new payment method
        $this->stripe->registerNewPaymentMethod($request, $user);

        return response('Done', 201);
    }

    /**
     * Delete user payment method
     *
     */
    public function delete($id)
    {
        $user = Auth::user();

        // Check if is demo
        if (is_demo($user->id)) {
            return Demo::response_204();
        }

        // Get payment method
        $paymentMethod = $user->findPaymentMethod($id);

        // Delete payment method
        $paymentMethod->delete();

        // Sync default payment method
        $user->updateDefaultPaymentMethodFromStripe();

        // Clear cached payment methods
        cache_forget_many([
            'payment-methods-user-' . $user->id,
            'default-payment-methods-user-' . $user->id
        ]);

        return response('Done!', 204);
    }
}
