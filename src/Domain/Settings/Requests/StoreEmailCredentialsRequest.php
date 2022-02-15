<?php
namespace Domain\Settings\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmailCredentialsRequest extends FormRequest
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
            'mailDriver'        => 'required|string',
            'smtp'              => 'sometimes|array',
            'ses'               => 'sometimes|array',
            'mailgun'           => 'sometimes|array',
            'postmark'          => 'sometimes|array',
        ];
    }
}
