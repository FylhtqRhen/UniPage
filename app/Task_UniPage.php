<?php

$url = "https://soundcloud.com/lakeyinspired/tracks";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$output = curl_exec($ch);
curl_close($ch);

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
