<?php

namespace App\Http\Requests\Oasis;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'avatar'       => 'sometimes|file|nullable',
            'name'         => 'required|string',
            'email'        => 'sometimes|email|nullable',
            'phone_number' => 'sometimes|string|nullable',
            'address'      => 'required|string',
            'city'         => 'required|string',
            'postal_code'  => 'required|string',
            'country'      => 'required|string',
            'ico'          => 'required|string',
            'dic'          => 'required|string',
            'ic_dph'       => 'required|string',
        ];
    }
}
