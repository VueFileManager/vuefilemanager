<?php
namespace Domain\SetupWizard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppSetupRequest extends FormRequest
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
            'title'             => 'required|string',
            'description'       => 'required|string',
            'logo'              => 'sometimes|file',
            'logo_horizontal'   => 'sometimes|file',
            'favicon'           => 'sometimes|file',
            'contactMail'       => 'required|email',
            'googleAnalytics'   => 'sometimes|string',
            'defaultStorage'    => 'sometimes|digits_between:1,9',
            'storageLimitation' => 'required|boolean',
        ];
    }
}
