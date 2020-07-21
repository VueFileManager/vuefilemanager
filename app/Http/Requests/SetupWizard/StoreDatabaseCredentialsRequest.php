<?php

namespace App\Http\Requests\SetupWizard;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatabaseCredentialsRequest extends FormRequest
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
            'connection' => 'required|string',
            'host'       => 'required|string',
            'port'       => 'required|string',
            'name'       => 'required|string',
            'username'   => 'required|string',
            'password'   => 'required|string',
        ];
    }
}
