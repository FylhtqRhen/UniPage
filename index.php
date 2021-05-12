<?php

//load environment
$vars = file('.env');
foreach ($vars as $var) {
    putenv(trim($var));
}

use App\DBConnection;
use App\Parser;
use App\Parsers\SoundCloudParse;

require_once 'vendor/autoload.php';

$parser = new Parser(new App\console\Helper(), DBConnection::get(), new SoundCloudParse());

$parser->parse();
$parser->save();
