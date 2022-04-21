<?php
namespace Domain\Files\Requests;

use Domain\Admin\Rules\DisabledMimetypes;
use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'name'      => 'required|string',
            'parent_id' => 'nullable|uuid',
            'path'      => 'sometimes|string',
            'is_last'   => 'sometimes|string',
            'extension' => 'sometimes|string|nullable',
            'file'      => ['required', 'file', new DisabledMimetypes],
        ];
    }
}
