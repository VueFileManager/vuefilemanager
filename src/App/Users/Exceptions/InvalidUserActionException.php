<?php
namespace App\Users\Exceptions;

use Exception;

class InvalidUserActionException extends Exception
{
    public $message = 'This user action is not allowed.';

    public function __construct()
    {
        parent::__construct();

        $this->message = __t('user_action_not_allowed');
    }
}
