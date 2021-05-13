<?php

namespace App\Handles\Exception;

use Throwable;

class TrackException extends BaseException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Ошибка при сохраненни трека $message";
        parent::__construct($message, $code, $previous);
    }
}