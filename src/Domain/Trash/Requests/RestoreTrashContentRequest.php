<?php
namespace Domain\Trash\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestoreTrashContentRequest extends FormRequest
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
            'to_home'      => 'sometimes|boolean',
            'items'        => 'array',
            'items.*.type' => 'required|string',
            'items.*.id'   => 'required|uuid',
        ];
    }
}
