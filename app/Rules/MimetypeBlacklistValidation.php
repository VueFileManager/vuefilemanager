<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;

class MimetypeBlacklistValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mimetype_blacklist = explode(',' ,get_setting('mimetypes_blacklist'));
        $file_mimetype = explode('/' ,$value->getMimeType());
        
        return !array_intersect($file_mimetype , $mimetype_blacklist);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
       abort (415,'Type of this mime type is not allowed.');
    }
}
