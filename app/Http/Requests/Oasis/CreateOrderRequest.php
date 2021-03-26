<?php

namespace App\Http\Requests\Oasis;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'ico'          => 'sometimes|nullable',
            'name'         => 'required|string',
            'email'        => 'required|email|unique:users',
            'phone_number' => 'string|nullable',
            'address'      => 'required|string',
            'state'        => 'required|string',
            'city'         => 'required|string',
            'postal_code'  => 'required|string',
            'country'      => 'required|string',
            'plan'         => 'required|string',
        ];
    }
}
