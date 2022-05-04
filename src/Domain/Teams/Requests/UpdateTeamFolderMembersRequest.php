<?php
namespace Domain\Teams\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamFolderMembersRequest extends FormRequest
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
            'members'                  => 'present|array',
            'members.*.permission'     => 'required|string',
            'members.*.id'             => 'required|uuid',
            'invitations'              => 'present|array',
            'invitations.*.email'      => 'required|email',
            'invitations.*.permission' => 'required|string',
            'invitations.*.type'       => 'required|string',
        ];
    }
}
