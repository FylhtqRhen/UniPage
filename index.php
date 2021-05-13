<?php

//load environment
$vars = file('.env');
foreach ($vars as $var) {
    putenv(trim($var));
}

use App\DBConnection;
use App\Parser;
use App\Parsers\SoundCloudParse;

require_once 'App/Handles/Handle.php';

require_once 'vendor/autoload.php';

$parser = new Parser(new App\Console\Helper(), DBConnection::get(), new SoundCloudParse());
$parser->parse();
$parser->save();
