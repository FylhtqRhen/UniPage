<?php

require_once '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$url0 = "https://soundcloud.com/lakeyinspired/tracks";
$url2 = "https://soundcloud.com/aljoshakonstanty/tracks";
$client_id = 'client_id=BgaaJYcFj3smIKozP7tEsKLBXr0hZP4E';


$tracks = [];


$client = new Client();


$request = new Request('GET', $url0);
$promise = $client->sendAsync($request)->then(function ($response) {
    return $response->getBody()->getContents();
});



$pattern = '/soundcloud:users:()\d+/';
preg_match($pattern, $promise->wait(), $key);
$trimmed = trim($key[0], 'soundcloud://users:');
$firstTrackUrl = 'https://api-v2.soundcloud.com/users/' . $trimmed . '/tracks?representation=&' . $client_id . '&limit=20&offset=0&linked_partitioning=1&app_version=1618476442&app_locale=en%5Bobject%20Object%5D';


$request = new Request('GET', "$firstTrackUrl");
$promise = $client->sendAsync($request)->then(function ($response) {
    return $response->getBody()->getContents();
});
$nodes = json_decode($promise->wait(), true);


foreach ($nodes["collection"] as $key => $node) {
    $tracks[] = $node;
}

do {
    $nextTrack = $nodes["next_href"] . '&' . $client_id;


    $request = new Request('GET', $nextTrack);
    $promise = $client->sendAsync($request)->then(function ($response) {
        return $response->getBody()->getContents();
    });
    $nodes = json_decode($promise->wait(), true);

    foreach ($nodes["collection"] as $key => $node) {
        $tracks[] = $node["title"];
    }

} while ($nodes["next_href"]);

var_dump($tracks);
