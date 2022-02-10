<?php
namespace Domain\SetupWizard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnvironmentSetupRequest extends FormRequest
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
            'storage'          => 'required|array',
            'storage.driver'   => 'required|string',
            'storage.key'      => 'sometimes|nullable|string',
            'storage.secret'   => 'sometimes|nullable|string',
            'storage.endpoint' => 'sometimes|nullable|string',
            'storage.region'   => 'sometimes|nullable|string',
            'storage.bucket'   => 'sometimes|nullable|string',
            'mailDriver'       => 'required|string',
            'mail'             => 'sometimes|array',
            'ses'              => 'sometimes|array',
            'mailgun'          => 'sometimes|array',
        ];
    }
}
