<?php

namespace App;

use App\DBConnection;

class Saver
{
    private $error;
    private $artistId;
    private $connect;

    public function __construct(\PDO $connect)
    {
        $this->connect = $connect;
    }

    public function saveArtist(array $artist): void
    {
        $this->artistId = $artist['id'];

        $fields = implode(', ', array_keys($artist));
        $values = array_map(function ($value) {
            return "'{$value}'";
        }, $artist);
        $values = implode(', ', $values);

        if ($this->existsArtist($artist['id'])) {
            $this->updateArtist($artist);
        } else {
            $this->connect->query("INSERT INTO media_artists ({$fields}) VALUES ({$values})");
        }
    }

    public function saveAllTrack(array $tracks): void
    {
        foreach ($tracks as $track) {
            if (!$this->existsTrack($track['id'])) {
                $this->saveTrack($track, $this->artistId);
            } else {
                $this->error['tracks'][] = $track;
            }
        }
    }

    private function saveTrack(array $track, string $artistId): void
    {
        $fields = implode(', ', array_keys($track));

        $values = array_map(function ($value) {
            return "'{$value}'";
        }, $track);
        $values = implode(', ', $values);

        $this->connect->query("INSERT INTO media_tracks (artist_id,{$fields}) VALUES ({$artistId}, {$values})");
    }

    private function existsRecords(string $table, $column, $value): bool
    {
        $result = $this->connect->query("SELECT count(*) FROM {$table} WHERE {$column} = '{$value}'");

        if ($result->fetchColumn() === 0) {
            return false;
        }

        return true;
    }

    private function existsTrack(string $track): bool
    {
        return $this->existsRecords('media_tracks', 'id', $track);
    }

    private function existsArtist(string $artist): bool
    {
        return $this->existsRecords('media_artists', 'id', $artist);
    }

    private function updateArtist(array $artist): void
    {
        foreach ($artist as $key => $value) {
            $this->connect->query("UPDATE media_artists SET {$key} = '{$value}'");
        }
    }
}
