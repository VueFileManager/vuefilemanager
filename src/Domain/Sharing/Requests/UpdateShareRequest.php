<?php
namespace Domain\Sharing\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShareRequest extends FormRequest
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
            'protected'  => 'required|boolean',
            'permission' => 'nullable|string',
            'expiration' => 'integer|nullable',
            'password'   => 'string',
        ];
    }
}
