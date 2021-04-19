<?php

//load environment
$vars = file('.env');
foreach ($vars as $var) {
    putenv(trim($var));
}

require_once 'vendor/autoload.php';

use App\Parsers\SoundCloudParse;
use App\Saver;
use App\DBConnection;
use App\Urls\SoundCloudUrl;
use GuzzleHttp\Client;

//example parser usage

$artist = 'lakeyinspired';

$parser = new SoundCloudParse($artist, new SoundCloudUrl(), new Client());
$artist = $parser->getArtistData();
$tracks = $parser->getTracksData();



//example saver usage
$connection = DBConnection::get();
$result = new Saver($connection);
$result->saveArtist($artist);
$result->saveAllTrack($tracks);
