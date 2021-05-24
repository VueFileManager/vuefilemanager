<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserByAdmin extends FormRequest
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
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:6|confirmed',
            'name'             => 'required|string|max:255',
            'storage_capacity' => 'required|digits_between:1,9',
            'role'             => 'required|string',
            'avatar'           => 'sometimes|file',
        ];
    }
}
