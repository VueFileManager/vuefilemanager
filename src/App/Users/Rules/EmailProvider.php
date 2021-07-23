<?php
namespace App\Users\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailProvider implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        $providers = config('disposable-email-providers');
        $provider = get_email_provider($value);

        return ! in_array($provider, $providers);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'This :attribute email provider is not accepted.';
    }
}
