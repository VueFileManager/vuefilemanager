<?php
namespace Domain\Admin\Rules;

use Illuminate\Contracts\Validation\Rule;

class DisabledMimetypes implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        $mimetype_blacklist = explode(',', get_settings('mimetypes_blacklist'));
        $file_mimetype = explode('/', $value->getMimeType());
        
        return ! array_intersect($file_mimetype, $mimetype_blacklist);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'Type of this mime type is not allowed.';
    }
}
