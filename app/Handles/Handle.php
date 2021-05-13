<?php

function exception_handler($exception)
{

    $class = get_class($exception);
    switch ($class) {
        case ('GuzzleHttp\Exception\ClientException') :
            echo 'Неверно введено имя артиста, проверьте ввод';
            break;
        case ('ArtistException.php') :
            echo 'Ошибка при сохранении артиста';
            break;
        case ('TrackException.php') :
            echo 'Ошибка при сохранении трека ';
            break;
    }
}
set_exception_handler('exception_handler');
