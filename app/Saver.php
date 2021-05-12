<?php

namespace App;

class Saver
{
    private $error;

    private $connect;

    public function __construct(\PDO $connect)
    {
        $this->connect = $connect;
    }

    public function saveArtist(array $artist): void
    {
        if ($this->existsArtist($artist['id'])) {
            $sql = "UPDATE media_artists SET 
                        avatar_url = :avatar_url,
                        first_name = :first_name,
                        followers_count = :followers_count,
                        full_name = :full_name,
                        id = :id,
                        kind = :kind,
                        last_modified = :last_modified,
                        last_name = :last_name,
                        permalink = :permalink,
                        permalink_url = :permalink_url,
                        uri = :uri,
                        urn = :urn,
                        username = :username,
                        verified = :verified,
                        city = :city,
                        country_code = :country_code,
                        station_permalink = :station_permalink";
            $this->error['artist'][] = $artist['full_name'] . ' успешно обновлен';
        } else {
            $sql = "INSERT INTO `media_artists` (avatar_url, first_name, followers_count, full_name, id, kind, last_modified, last_name, permalink, permalink_url, uri, urn, username, verified, city, country_code, station_permalink)
                    VALUES (:avatar_url, :first_name, :followers_count, :full_name, :id, :kind, :last_modified, :last_name, :permalink, :permalink_url, :uri, :urn, :username, :verified, :city, :country_code, :station_permalink)";
            $this->error['artist'][] = $artist['full_name'] . ' успешно сохранен';
        }
        $query = $this->connect->prepare($sql);
        $query->execute($artist);
        echo ('Артист ' . $this->error['artist'][0] . PHP_EOL);
    }

    public function saveTracks(array $tracks): void
    {
        foreach ($tracks as $track) {
            if (!$this->existsTrack($track['id'])) {
                $sql = "INSERT INTO `media_tracks` (artist_id, id, track_name, created_at, genre, display_date, track_state, track_format, uri) 
                        VALUES (:artist_id,  :id, :track_name, :created_at, :genre, :display_date, :track_state, :track_format, :uri)";
                $this->error['tracks'][] = $track['track_name'] . ' успешно сохранен';
            } else {
                $this->error['tracks'][] = $track['track_name'] . ' уже есть в базе';
            }
        }
        $query = $this->connect->prepare($sql);
        $query->execute($tracks);
        foreach ($this->error['tracks'] as $track) {
            echo 'Трек ' . $track . PHP_EOL;
        }
    }

    private function existsRecords(string $table, int $id): bool
    {
        $sql = "SELECT count(*) FROM {$table} WHERE `id` = :id";
        $query = $this->connect->prepare($sql);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch();
        if ($result['count(*)'] == 0) {
            return false;
        }
        return true;
    }

    private function existsTrack(int $track): bool
    {
        return $this->existsRecords('media_tracks', $track);
    }

    private function existsArtist(int $artist): bool
    {
        return $this->existsRecords('media_artists', $artist);
    }
}
