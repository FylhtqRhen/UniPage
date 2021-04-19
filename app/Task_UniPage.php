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

$actor = $nodes["collection"][0]['user'];
var_dump($actor);
do {
    foreach ($nodes["collection"] as $node) {
        $tracks[] = [
            $node["title"] => [
                "name" => $node["title"],
                "created_at" => $node["created_at"],
                "genre" => $node["genre"],
                "display_date" => $node["display_date"],
                "state" => $node["state"],
                "track_format" => $node["track_format"],
                "uri" => $node["uri"],
            ]
        ];
    }
    $nextTrack = $nodes["next_href"] . '&' . $client_id;
    $request = new Request('GET', $nextTrack);
    $promise = $client->sendAsync($request)->then(function ($response) {
        return $response->getBody()->getContents();
    });
    $nodes = json_decode($promise->wait(), true);
} while ($nodes["next_href"]);

var_dump($tracks);
