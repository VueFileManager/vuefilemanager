<?php
namespace Domain\UploadRequest\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadRequest extends FormRequest
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
            'email'     => 'sometimes|email',
            'notes'     => 'sometimes|string',
            'folder_id' => 'sometimes|uuid',
            'name'      => 'sometimes|string',
        ];
    }
}
