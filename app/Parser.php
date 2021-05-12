<?php

namespace App;

use App\Parsers\ParseInterface;
use App\Console\Helper;

class Parser
{
    /**
     * @var string
     */
    private $artist;

    /**
     * @var array
     */
    private $artistData;

    /**
     * @var array
     */
    private $tracksData;

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var ParseInterface
     */
    private $parser;

    /**
     * Parser constructor.
     * @param Helper $artist
     * @param \PDO $connection
     * @param ParseInterface $parser
     */
    public function __construct(Helper $artist, \PDO $connection, ParseInterface $parser)
    {
        $this->artist = $artist->input();
        $this->connection = $connection;
        $this->parser = $parser;
    }

    /**
     *
     */
    public function parse(): void
    {
        $this->parser->setArtist($this->artist);
        $this->artistData = $this->parser->getArtistData();
        $this->tracksData = $this->parser->getTracksData();
    }

    /**
     *
     */
    public function save(): void
    {
        $result = new Saver($this->connection);
        $result->saveArtist($this->artistData);
        $result->saveTracks($this->tracksData);
    }
}