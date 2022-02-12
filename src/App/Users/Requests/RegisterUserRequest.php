<?php
namespace App\Users\Requests;

use App\Users\Rules\EmailProvider;
use App\Users\Rules\ReCaptchaRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;
use App\Users\Rules\PasswordValidationRules;

class RegisterUserRequest extends FormRequest
{
    use PasswordValidationRules;

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
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email', new EmailProvider],
            'name'      => 'required|string|max:255',
            'password'  => $this->passwordRules(),
            'reCaptcha' => [new RequiredIf(get_settings('allowed_recaptcha') == 1), 'string', 'nullable', app(ReCaptchaRules::class)],
        ];
    }
}
