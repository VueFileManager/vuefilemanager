<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentCardCollection;
use App\Http\Resources\PaymentCardResource;
use App\Http\Resources\PaymentDefaultCardResource;
use App\Services\PaymentService;
use App\UserCard;
use Auth;
use Illuminate\Http\Request;
use Laravel\Cashier\PaymentMethod;

class PaymentCardsController extends Controller
{
    /**
     * @var PaymentService
     */
    private $payment;

    /**
     * PaymentCardsController constructor.
     */
    public function __construct(PaymentService $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Update card detail
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        // Update DefaultPayment Method
        $user->updateDefaultPaymentMethod($id);

        // Sync default payment method
        $user->updateDefaultPaymentMethodFromStripe();
    }

    /**
     * Delete user credit card
     *
     */
    public function delete($id)
    {
        $user = Auth::user();

        // Get payment method
        $paymentMethod = $user->findPaymentMethod($id);

        // Delete payment method
        $paymentMethod->delete();

        // Sync default payment method
        $user->updateDefaultPaymentMethodFromStripe();

        return response('Done!', 204);
    }

    /**
     * Get user payment methods sorted by default and others
     *
     * @return array
     */
    public function payment_methods()
    {
        $user = Auth::user();

        // Get default payment method
        $defaultPaymentMethodObject = $user->defaultPaymentMethod();

        $defaultPaymentMethod = $defaultPaymentMethodObject instanceof PaymentMethod
            ? $defaultPaymentMethodObject->asStripePaymentMethod()
            : $defaultPaymentMethodObject;

        // filter payment methods without default payment
        $paymentMethods = $user->paymentMethods()->filter(function ($paymentMethod) use ($defaultPaymentMethod) {
            return $paymentMethod->id !== $defaultPaymentMethod->id;
        });

        // Get payment methods
        $paymentMethodsMapped = $paymentMethods->map(function ($paymentMethod) {
            return $paymentMethod->asStripePaymentMethod();
        })->values()->all();

        if (is_null($paymentMethodsMapped) && is_null($paymentMethodsMapped)) {
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
}
