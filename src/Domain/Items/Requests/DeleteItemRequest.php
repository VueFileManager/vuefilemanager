<?php
namespace Domain\Items\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteItemRequest extends FormRequest
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
            'data[*].force_delete' => 'required|boolean',
            'data[*].type'         => 'required|string',
            'data[*].id'           => 'required|integer',
        ];
    }
}
