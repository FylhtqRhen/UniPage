<?php

namespace App\Parsers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Urls\SoundCloudUrl;

class SoundCloudParse implements ParseInterface
{
    private $urlHelper;

    private $client;

    private $actor;

    private $tracks = [];

    public function __construct(string $actor, SoundCloudUrl $urlHelper, Client $client)
    {
        $this->actor = $actor;
        $this->urlHelper = $urlHelper;
        $this->client = $client;
    }

    private function sendRequest(string $url)
    {
        $request = new Request('GET', $url);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            return $response->getBody()->getContents();
        });
        return $promise->wait();
    }

    private function getBaseParams(): string
    {
        $pattern = '/soundcloud:users:()\d+/';
        $baseUrl = $this->urlHelper->getBaseUrl($this->actor);
        $params = $this->sendRequest($baseUrl);;
        preg_match($pattern, $params, $key);

        return trim($key[0], 'soundcloud://users:');
    }

    private function getTrack(): array
    {
        $params = $this->getBaseParams();
        $firstUrl = $this->urlHelper->getFirstUrl($params);
        $finds = $this->sendRequest($firstUrl);

        return json_decode($finds, true);
    }

    public function getArtistData(): array
    {
        return $this->getTrack()["collection"][0]['user'];
    }

    public function getTracksData(): array
    {
        $nodes = $this->getTrack();
        do {
            foreach ($nodes["collection"] as $node) {
                $this->tracks[] = [
                        "id" => $node["id"],
                        "name" => $node["title"],
                        "created_at" => $node["created_at"],
                        "genre" => $node["genre"],
                        "display_date" => $node["display_date"],
                        "state" => $node["state"],
                        "track_format" => $node["track_format"],
                        "uri" => $node["uri"],
                ];
            }
            $nextHref = $nodes["next_href"];
            $nextUrl = $this->urlHelper->getNextUrl($nextHref);
            $nextTrack = $this->sendRequest($nextUrl);
            $nodes = json_decode($nextTrack, true);

        } while ($nodes["next_href"]);

        return $this->tracks;
    }
}
