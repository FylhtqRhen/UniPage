<?php

namespace App\Parsers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Urls\SoundCloudUrl;

class SoundCloudParse implements ParseInterface
{
    const PATTERN = '/soundcloud:users:()\d+/';

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

    private function getArtistId(): string
    {
        $baseUrl = $this->urlHelper->getArtistBaseUrl($this->actor);
        $params = $this->sendRequest($baseUrl);;
        preg_match(self::PATTERN, $params, $key);

        return trim($key[0], 'soundcloud://users:');
    }

    private function getTrack(): array
    {
        $params = $this->getArtistId();
        $firstUrl = $this->urlHelper->getFirstCollectionTracksUrl($params);
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
            $nextUrl = $this->urlHelper->getNextCollectionTracksUrl($nextHref);
            $nextTrack = $this->sendRequest($nextUrl);
            $nodes = json_decode($nextTrack, true);

        } while ($nodes["next_href"]);

        return $this->tracks;
    }
}
