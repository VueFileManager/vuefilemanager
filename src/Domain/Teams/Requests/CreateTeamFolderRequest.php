<?php
namespace Domain\Teams\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamFolderRequest extends FormRequest
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
            'name'                     => 'required|string',
            'invitations'              => 'required|array',
            'invitations.*.email'      => 'required|email',
            'invitations.*.permission' => 'required|string',
            'invitations.*.type'       => 'required|string',
        ];
    }
}
