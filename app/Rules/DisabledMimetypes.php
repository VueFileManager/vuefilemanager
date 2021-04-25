<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DisabledMimetypes implements Rule
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
        $mimetype_blacklist = explode(',', get_setting('mimetypes_blacklist'));
        $file_mimetype = explode('/', $value->getMimeType());
        
        return ! array_intersect($file_mimetype, $mimetype_blacklist);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Type of this mime type is not allowed.';
    }
}
