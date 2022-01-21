<?php
namespace App\Users\Rules;

use Auth;
use Hash;
use Illuminate\Contracts\Validation\Rule;

class PasswordMatchWithCurrent implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return Hash::check($value, Auth::user()->password);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return "Your current password doesn't match.";
    }
}
