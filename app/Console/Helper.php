<?php

namespace App\Console;

class Helper
{
    /**
     * @var false|resource
     */
    private $input;

    /**
     * @var false|resource
     */
    private $output;

    /**
     * Helper constructor.
     */
    public function __construct()
    {
        $this->input = fopen('php://stdin', 'r');
        $this->output = fopen('php://stdout', 'w');
    }

    /**
     * @return string
     */
    public function input(): string
    {
        fwrite($this->output, 'Введите имя артиста без пробелов: ');
        fscanf($this->input, '%s', $artist);
        $artist = preg_replace("/[.,]/", '', $artist);
        $artist = preg_replace("/\W+[^0-9-]/", '', $artist);
        $artist = preg_replace('/\s+/', '', $artist);
        return mb_strtolower($artist);
    }
}
