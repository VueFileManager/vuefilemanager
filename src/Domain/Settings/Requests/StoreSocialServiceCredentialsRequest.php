<?php
namespace Domain\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialServiceCredentialsRequest extends FormRequest
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
            'client_id'     => 'required|string',
            'client_secret' => 'required|string',
            'service'       => 'required|string',
        ];
    }
}
