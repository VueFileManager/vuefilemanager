<?php
namespace Domain\SetupWizard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminAccountRequest extends FormRequest
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
            'email'         => 'required|string|email|unique:users',
            'password'      => 'required|string|min:6|confirmed',
            'name'          => 'required|string',
            'purchase_code' => 'required|string',
            'license'       => 'required|string',
            'avatar'        => 'sometimes|file',
        ];
    }
}
