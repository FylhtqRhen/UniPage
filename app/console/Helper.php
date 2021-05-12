<?php

namespace App\console;

class Helper
{
    private $input;

    private $output;

    public function __construct()
    {
        $this->input = fopen('php://stdin', 'r');
        $this->output = fopen('php://stdout', 'w');
    }

    public function input(): string
    {
        fwrite($this->output, 'Введите имя артиста без пробелов: ');
        fscanf($this->input, '%s', $artist);
        $artist = preg_replace("/[.,]/", '', $artist);
        $artist = preg_replace("/\W+/", '', $artist);
        $artist = preg_replace('/\s+/', '', $artist);
        return mb_strtolower($artist);
    }
}
