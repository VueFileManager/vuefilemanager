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
            'filename'  => 'required|string',
            'folder_id' => 'nullable|uuid',
            'is_last'   => 'sometimes|boolean',
            'file'      => ['required', 'file', new DisabledMimetypes],
        ];
    }
}
