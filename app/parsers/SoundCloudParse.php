<?php

namespace app\parsers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Helpers\Parsers\ParseInterface;
use Helpers\Urls\SoundCloudUrl;

class SoundCloudParse implements ParseInterface
{
    private SoundCloudUrl $urlHelper;

    private Client $client;

    private string $actor;

    private array $tracks;

    public function __construct($actor)
    {
        $this->actor = $actor;
        $this->client = new Client();
        $this->urlHelper = new SoundCloudUrl();
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
        $promise = $this->sendRequest($baseUrl);
        $params = $promise->wait();
        preg_match($pattern, $params, $key);

        return trim($key[0], 'soundcloud://users:');
    }

    private function getTrack(): array
    {
        $params = $this->getBaseParams();
        $firstUrl = $this->urlHelper->getFirstUrl($params);
        $finds = $this->sendRequest($firstUrl)->wait();

        return json_decode($finds, true);
    }

    public function getActorData(): array
    {
        return $this->getTrack()["collection"][0]['user'];
    }

    public function getTrackData(): array
    {
        $nodes = $this->getTrack();
        do {
            foreach ($nodes["collection"] as $node) {
                $this->tracks[] = [
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
            $nextHref = $nodes["next_href"];
            $nextUrl = $this->urlHelper->getNextUrl($nextHref);
            $nextTrack = $this->sendRequest($nextUrl)->wait();
            $nodes = json_decode($nextTrack, true);

        } while ($nodes["next_href"]);

        return $this->tracks;
    }
}
