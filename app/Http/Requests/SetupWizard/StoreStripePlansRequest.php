<?php

namespace App\Http\Requests\SetupWizard;

use Illuminate\Foundation\Http\FormRequest;

class StoreStripePlansRequest extends FormRequest
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
            'plans'                          => 'required|array',
            'plans.*.type'                   => 'required|string',
            'plans.*.attributes.name'        => 'required|string',
            'plans.*.attributes.price'       => 'required|string',
            'plans.*.attributes.description' => 'sometimes|nullable|string',
            'plans.*.attributes.capacity'    => 'required|digits_between:1,9',
        ];
    }
}
