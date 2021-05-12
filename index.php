<?php

//load environment
$vars = file('.env');
foreach ($vars as $var) {
    putenv(trim($var));
}

require_once 'vendor/autoload.php';

$parser = new \App\Parser();

$parser->parse();
$parser->save();

die();

//
//
//$artist = (new App\console\Helper)->input();
//
////$artist = 'lakeyinspired';
//
//$parser = new SoundCloudParse($artist, new SoundCloudUrl(), new Client());
//
//$artist = $parser->getArtistData();
//$tracks = $parser->getTracksData();
//
//$connection = DBConnection::get();
//$result = new Saver($connection);
//$result->saveArtist($artist);
//$result->saveTracks($tracks);
//
//




