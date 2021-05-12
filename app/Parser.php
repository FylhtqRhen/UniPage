<?php


namespace App;

use App\Parsers\SoundCloudParse;
use App\Saver;
use App\DBConnection;
use App\Urls\SoundCloudUrl;
use GuzzleHttp\Client;
use App\console\Helper;


class Parser
{
    private $artist;
    private $artistData;
    private $tracksData;
    private $connection;
    private $parser;

    public function __construct()
    {
        $this->artist = (new console\Helper)->input();
        $this->connection = DBConnection::get();
        $this->parser = new SoundCloudParse($this->artist, new SoundCloudUrl(), new Client());
    }

    public function parse(): void
    {
        $this->artistData = $this->parser->getArtistData();
        $this->tracksData = $this->parser->getTracksData();
    }

    public function save(): void
    {
        $result = new Saver($this->connection);
        $result->saveArtist($this->artistData);
        $result->saveTracks($this->tracksData);
    }
}