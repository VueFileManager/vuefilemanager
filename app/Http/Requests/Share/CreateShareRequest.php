<?php
namespace App\Http\Requests\Share;

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
            'isPassword' => 'required|boolean',
            'type' => 'required|string',
            'expiration' => 'integer|nullable',
            'permission' => 'string',
            'password' => 'string',
            'emails.*' => 'email',
        ];
    }
}
