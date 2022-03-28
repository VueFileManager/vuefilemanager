<?php
namespace Domain\Homepage\Requests;

use App\Users\Rules\ReCaptchaRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class SendContactMessageRequest extends FormRequest
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
            'email'     => 'required|email',
            'message'   => 'required|string',
            'reCaptcha' => [new RequiredIf(get_settings('allowed_recaptcha') == 1), 'string', 'nullable', app(ReCaptchaRules::class)],
        ];
    }
}
