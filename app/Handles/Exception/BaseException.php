<?php

namespace App\Handles\Exception;

use Exception;
use Throwable;

abstract class BaseException extends Exception
{
    public function __construct($message = "Ошибка при обработке", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}