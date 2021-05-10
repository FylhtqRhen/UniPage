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
        $node = $this->getTrack()["collection"][0]['user'];

        $artistData = [
            'avatar_url' => $node['avatar_url'],
            'first_name' => $node['first_name'],
            'followers_count' => $node['followers_count'],
            'full_name' => $node['full_name'],
            'id' => $node['id'],
            'kind' => $node['kind'],
            'last_modified' => $node['last_modified'],
            'last_name' => $node['last_name'],
            'permalink' => $node['permalink'],
            'permalink_url' => $node['permalink_url'],
            'uri' => $node['uri'],
            'urn' => $node['urn'],
            'username' => $node['username'],
            'verified' => $node['verified'],
            'city' => $node['city'],
            'country_code' => $node['country_code'],
            'station_permalink' => $node['station_permalink']
        ];
        return $artistData;
    }

    public function getTracksData(): array
    {
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

    private function sendRequest(string $url): string
    {
        $request = new Request('GET', $url);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            return $response->getBody()->getContents();
        });
        return $promise->wait();
    }
}
