<?php
namespace VueFileManager\Subscription\Domain\Credits\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    protected $message = 'There is not sufficient balance';
}
