<?php
namespace Domain\Sharing\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateShareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'         => 'required|uuid',
            'isPassword' => 'sometimes|boolean',
            'password'   => 'required_if:isPassword,true',
            'type'       => 'required|string',
            'expiration' => 'sometimes|integer',
            'permission' => 'sometimes|string',
            'emails'     => 'sometimes|array',
            'emails.*'   => 'email',
        ];
    }
}
