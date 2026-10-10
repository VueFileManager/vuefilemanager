<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Stripe\Exceptions;

use Exception;

class ChargeFailedException extends Exception
{
    protected $message = 'Unfortunately, the charge failed';
}
