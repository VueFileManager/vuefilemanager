<?php
namespace App\Users\Exceptions;

use Exception;

class InvalidUserActionException extends Exception
{
    // TODO: translate
    protected $message = 'This user action is not allowed.';
}
