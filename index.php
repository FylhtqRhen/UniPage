<?php

use app\parsers\SoundCloudParse;
use app\Saver;

require_once 'vendor/autoload.php';



$parser = new SoundCloudParse();
$tracks = $parser->getTrackData();
$artist = $parser->getActorData();

$result = new Saver();

$result->saveArtist($artist);
$result->saveAllTrack($tracks);

