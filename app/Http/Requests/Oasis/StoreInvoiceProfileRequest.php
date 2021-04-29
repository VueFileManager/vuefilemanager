<?php
namespace App\Http\Requests\Oasis;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => 'sometimes|file',
            'stamp' => 'sometimes|file',
            'company' => 'required|string',
            'email' => 'required|email',
            'ico' => 'sometimes|string|nullable',
            'dic' => 'sometimes|string|nullable',
            'ic_dph' => 'sometimes|string|nullable',
            'registration_notes' => 'sometimes|string|nullable',
            'author' => 'required|string',
            'address' => 'required|string',
            'state' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'phone' => 'required|string',
            'bank' => 'required|string',
            'iban' => 'required|string',
            'swift' => 'required|string',
        ];
    }
}
