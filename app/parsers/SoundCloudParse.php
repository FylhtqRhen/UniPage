<?php

namespace App\Parsers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use App\Urls\SoundCloudUrl;
use Psr\Http\Message\ResponseInterface;

class SoundCloudParse implements ParseInterface
{
    const PATTERN = '/soundcloud:users:()\d+/';

    private $urlHelper;

    private $client;

    private $artist;

    private $tracks = [];

    public function __construct(string $artist, SoundCloudUrl $urlHelper, Client $client)
    {
        $this->artist = $artist;
        $this->urlHelper = $urlHelper;
        $this->client = $client;
    }

    public function getArtistData(): array
    {
        $this->checkArtist();
        $node = $this->getTrack()["collection"][0]['user'];
        $a = [
            'avatar_url',
            'first_name',
            'followers_count',
            'full_name',
            'id',
            'kind',
            'last_modified',
            'last_name',
            'permalink',
            'permalink_url',
            'uri',
            'urn',
            'username',
            'verified',
            'city',
            'country_code',
            'station_permalink',
        ];

        return array_intersect_key($node, array_flip($a));
    }

    public function getTracksData(): array
    {
        $this->checkArtist();

        $nodes = $this->getTrack();
        do {
            foreach ($nodes["collection"] as $node) {
                $this->tracks[] = [
                    "artist_id" => $node["user_id"],
                    "id" => $node["id"],
                    "track_name" => $node["title"],
                    "created_at" => $node["created_at"],
                    "genre" => $node["genre"],
                    "display_date" => $node["display_date"],
                    "track_state" => $node["state"],
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

    private function getArtistId(): string
    {
        $baseUrl = $this->urlHelper->getArtistBaseUrl($this->artist);
        $params = $this->sendRequest($baseUrl);
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

    private function sendRequest(string $url): string
    {
        $request = new Request('GET', $url);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            return $response->getBody()->getContents();
        });
        return $promise->wait();
    }

    private function checkArtist()
    {
        $promise = $this->client->requestAsync('GET', $this->urlHelper->getArtistBaseUrl($this->artist));
        $promise
            ->then(function (RequestException $e) {
                throw $e;
            })
            ->then(
                function ($someVal) {
                },
                function (RequestException $e) {
                    echo $e = 'Имя артиста введено неверно';
                    die();
                });
    }
}
