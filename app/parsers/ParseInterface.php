<?php

namespace Helpers\Parsers;

interface ParseInterface
{
    public function getTrackData() : array;

    public function getActorData() : array;
}
