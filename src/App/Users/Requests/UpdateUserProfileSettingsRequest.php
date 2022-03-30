<?php
namespace App\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileSettingsRequest extends FormRequest
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
            'avatar' => 'sometimes|file',
            'name'   => 'string',
            'value'  => 'string|nullable',
        ];
    }
}
