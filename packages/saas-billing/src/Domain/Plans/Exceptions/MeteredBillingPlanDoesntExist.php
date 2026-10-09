<?php
namespace VueFileManager\Subscription\Domain\Plans\Exceptions;

use Exception;

class MeteredBillingPlanDoesntExist extends Exception
{
    protected $message = "Metered billing plan doesn't exist.";

    protected $code = 403;
}
