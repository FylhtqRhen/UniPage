<?php

namespace app;

use Databases\DBHelper;

class Saver
{

    private array $error;
    private int $artistId;
    private DBHelper $connect;

    public function __construct()
    {
        $this->connect = DBHelper::getConnect();
    }

    public function saveArtist($artist)
    {
        $this->artistId = $artist['id'];
        $fields = implode(',', array_keys($artist));
        $values = implode(',', $artist);

        if ($this->checkArtist($artist)) {
            $this->updateArtist($artist);
        } else {
            "INSERT INTO media_artists FIELDS $fields VALUES $values";
        }
    }

    public function saveAllTrack(array $tracks)
    {
        foreach ($tracks as $track) {
            if (!$this->checkTrack($track)) {
                $this->saveTrack($track, $this->artistId);
            } else {
                $this->error['tracks'][] = $track;
            }
        }
    }

    private function checkTrack(mixed $track): bool
    {
        $name = $track['name'];
        if ("SELECT * FROM media_tracks WHERE name = $name") {
            return true;
        }  else return false;
    }

    private function checkArtist($artist): string
    {
        return "SELECT * FROM media_artists WHERE username = $artist";
    }

    private function updateArtist($artist): void
    {
        foreach ($artist as $key => $value){
            "UPDATE media_artists SET $artist[$key] = $value";
        }
    }

    public function saveTrack($track, $artistId)
    {
        $fields = implode(',', array_keys($track));
        $values = implode(',', $track);
        $a = "INSERT INTO tracks FIELDS (artist_id,$fields) VALUES ($artistId,$values)";
        if (!$a) {
            $this->error['save'][] = $track['name'];
        }
    }

    public function normalizeErrors()
    {

    }
}