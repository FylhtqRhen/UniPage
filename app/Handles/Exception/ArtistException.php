<?php

namespace App\Handles\Exception;

use Throwable;

class ArtistException extends BaseException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Ошибка при сохраненни артиста $message";
        parent::__construct($message, $code, $previous);
    }
}