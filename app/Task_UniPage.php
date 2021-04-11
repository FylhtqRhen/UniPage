<?php

require_once '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

//
//$url1 = "https://api-v2.soundcloud.com/tracks?ids=317754282&client_id=sBsbgNTdaqhwPNN5npZXiLAalFrvSI1S&%5Bobject%20Object%5D=&app_version=1617968068&app_locale=en";
//$url2 = "https://api-v2.soundcloud.com/tracks?ids=317754282&client_id=sBsbgNTdaqhwPNN5npZXiLAalFrvSI1S&%5Bobject%20Object%5D=&app_version=1617968068&app_locale=en";
//
$url = "https://api-v2.soundcloud.com/users/103470313/tracks?representation=&client_id=sBsbgNTdaqhwPNN5npZXiLAalFrvSI1S&limit=20&offset=0&linked_partitioning=1&app_version=1617968068&app_locale=en";

$url0 = "https://soundcloud.com/lakeyinspired/tracks";
$url2 = "https://soundcloud.com/aljoshakonstanty/tracks";
$url3 = "https://soundcloud.com/birocratic";
$url4 = "https://soundcloud.com/dixxy-2";
$url5 = "https://soundcloud.com/dekobe";


$client = new Client();
$request = new Request('GET', "$url0");
$promise = $client->sendAsync($request)->then(function ($response) {
    return $response->getBody()->getContents();
});

$output = $promise->wait();

$nodes = json_decode($output, true);

//var_dump($output);

$html = file_get_html($url0);

var_dump($html);

$finder = $html->find('meta [content = "soundcloud//users"]');


var_dump($finder);

die;


$dom = new DOMDocument();
$dom->loadHTML($url0);

$finders = new DOMXPath($dom);

$nodes = $finders->query('html/head//meta[@content="soundcloud//users"]');




$tracks = [];

$client_id = 'client_id=sBsbgNTdaqhwPNN5npZXiLAalFrvSI1S';

var_dump($nodes["collection"][0]["title"]);

var_dump($nodes["next_href"]);

$nextTrack = $nodes["next_href"] . '&' . $client_id;

$request = new Request('GET', "$nextTrack");
$promise = $client->sendAsync($request)->then(function ($response) {
    return $response->getBody()->getContents();
});

$output = $promise->wait();

$nodes = json_decode($output, true);

var_dump($nodes["collection"][0]["title"]);

var_dump($nodes["next_href"]);

die;


foreach ($nodes[0] as $key) {
    foreach ($nodes[$key] as $kuy => $name) {
        if ($kuy == "title") {
                $tracks[] = $name;
            }
        }
    }


var_dump($tracks);

die;



$dom = new DOMDocument();
$dom->loadHTML($output);
$finder = new DOMXPath($dom);

$nodes = $finder->query("//article[@class='audible']/h2/a[@itemprop=\"url\"]");

$tracks = [];

for ($i = 0; $i<$nodes->length; $i++) {
    $a = $nodes->item($i);
    $tracks[] = $a->nodeValue;
}

var_dump($tracks);

//var_dump($promise);

die;










$url = "https://soundcloud.com/lakeyinspired/tracks";

$client = new Client();

$request = new Request('GET', "$url");

$response = $client->send($request);

var_dump($response);
die();


$promise = $client->sendAsync($request)->then(function ($response) {
    $response->getContents();
});
var_dump($promise);
die();



$promise->wait();

var_dump($text = $promise->wait());


//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//$output = curl_exec($ch);
//curl_close($ch);
//
//$dom = new DOMDocument();
//$dom->loadHTML($output);
//$finder = new DOMXPath($dom);
//
//$nodes = $finder->query("//article[@class='audible']/h2/a[@itemprop=\"url\"]");
//
//$tracks = [];
//
//for ($i = 0; $i<$nodes->length; $i++) {
//    $a = $nodes->item($i);
//    $tracks[] = $a->nodeValue;
//}
//
//var_dump($tracks);
