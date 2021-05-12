<?php


namespace App;

use App\Parsers\ParseInterface;
use App\Saver;
use App\console\Helper;

class Parser
{
    private $artist;

    private $artistData;

    private $tracksData;

    private $connection;

    private $parser;

    public function __construct(Helper $artist, \PDO $connection, ParseInterface $parser)
    {
        $this->artist = $artist->input();
        $this->connection = $connection;
        $this->parser = $parser;
    }

    public function parse(): void
    {
        $this->parser->setArtist($this->artist);
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