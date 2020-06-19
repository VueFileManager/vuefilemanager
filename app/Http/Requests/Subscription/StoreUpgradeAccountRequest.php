<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpgradeAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Billings
            'billing'                                 => 'required|array',
            'billing.billing_address'                 => 'required|string',
            'billing.billing_city'                    => 'required|string',
            'billing.billing_country'                 => 'required|string',
            'billing.billing_name'                    => 'required|string',
            'billing.billing_phone_number'            => 'required|string',
            'billing.billing_postal_code'             => 'required|string',
            'billing.billing_state'                   => 'required|string',

            // Payment
            'payment'                                 => 'required|array',
            'payment.type'                            => 'required|string',
            'payment.meta'                            => 'required|sometimes|array',
            'payment.meta.pm'                         => 'required|sometimes|string',

            // Plan
            'plan.data'                               => 'required|array',
            'plan.data.attributes'                    => 'required|array',
            'plan.data.attributes.capacity'           => 'required|digits_between:1,9',
            'plan.data.attributes.capacity_formatted' => 'required|string',
            'plan.data.attributes.currency'           => 'required|string',
            'plan.data.attributes.description'        => 'required|string',
            'plan.data.attributes.name'               => 'required|string',
            'plan.data.attributes.price'              => 'required|string',
            'plan.data.id'                            => 'required|string',
            'plan.data.type'                          => 'required|string',
        ];
    }
}
