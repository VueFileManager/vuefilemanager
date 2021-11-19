<?php
namespace Domain\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeStorageCapacityRequest extends FormRequest
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
            'attributes'                    => 'required|array',
            'attributes.max_storage_amount' => 'required|digits_between:1,9',
        ];
    }
}
