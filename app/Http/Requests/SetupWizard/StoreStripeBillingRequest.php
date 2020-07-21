<?php

namespace App\Http\Requests\SetupWizard;

use Illuminate\Foundation\Http\FormRequest;

class StoreStripeBillingRequest extends FormRequest
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
            'billing_phone_number' => 'sometimes|nullable|string',
            'billing_postal_code'  => 'required|string',
            'billing_vat_number'   => 'required|string',
            'billing_address'      => 'required|string',
            'billing_country'      => 'required|string',
            'billing_state'        => 'required|string',
            'billing_city'         => 'required|string',
            'billing_name'         => 'required|string',
        ];
    }
}
