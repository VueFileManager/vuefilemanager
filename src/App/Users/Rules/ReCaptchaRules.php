<?php
namespace App\Users\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;
 
class ReCaptchaRules implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret'   => config('services.recaptcha.client_secret'),
                    'remoteip' => request()->getClientIp(),
                    'response' => $value,
                ],
            ]
        );
        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    public function message(): string
    {
        return 'Are you a robot?';
    }
}
