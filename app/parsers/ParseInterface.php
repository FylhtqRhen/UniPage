<?php

namespace App\Parsers;

interface ParseInterface
{
    public function getTracksData() : array;

    public function getArtistData() : array;
}
