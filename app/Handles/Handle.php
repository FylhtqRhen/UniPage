<?php

use App\Handles\Exception\BaseException;
use GuzzleHttp\Exception\ClientException;

function exceptionHandler($exception)
{
    if ($exception instanceof BaseException) {
        echo $exception->getMessage();
    } elseif ($exception instanceof ClientException) {
        echo 'Ошибка при обработке';
    } else {
        echo 'Неверно введено имя артиста, проверьте ввод';
    }
}
set_exception_handler('exceptionHandler');
