<?php
namespace Domain\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentServiceCredentialsRequest extends FormRequest
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
            'key'     => 'required|string',
            'secret'  => 'required|string',
            'webhook' => 'sometimes|string',
            'live'    => 'sometimes|nullable|boolean',
        ];
    }
}
