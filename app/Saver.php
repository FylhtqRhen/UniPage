<?php

namespace App;

class Saver
{
    /**
     * @var
     */
    private $error;

    /**
     * @var \PDO
     */
    private $connect;

    /**
     * Saver constructor.
     * @param \PDO $connect
     */

    public function __construct(\PDO $connect)
    {
        $this->connect = $connect;
    }

    /**
     * @param array $artist
     */
    public function saveArtist(array $artist): void
    {
        if ($this->existsArtist($artist['id'])) {
            $sql = "UPDATE media_artists SET 
                        avatar_url = :avatar_url,
                        first_name = :first_name,
                        followers_count = :followers_count,
                        full_name = :full_name,
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
            $this->error['artist'][] = $artist['username'] . ' успешно обновлен';
            unset($artist['id']);
        } else {
            $sql = "INSERT INTO `media_artists` (avatar_url, first_name, followers_count, full_name, id, kind, last_modified, last_name, permalink, permalink_url, uri, urn, username, verified, city, country_code, station_permalink)
                    VALUES (:avatar_url, :first_name, :followers_count, :full_name, :id, :kind, :last_modified, :last_name, :permalink, :permalink_url, :uri, :urn, :username, :verified, :city, :country_code, :station_permalink)";
            $this->error['artist'][] = $artist['username'] . ' успешно сохранен';
        }
        $query = $this->connect->prepare($sql);
        $query->execute($artist);
        echo $this->error['artist'][0] . PHP_EOL;
    }

    /**
     * @param array $tracks
     */
    public function saveTracks(array $tracks): void
    {
        foreach ($tracks as $track) {
            if (!$this->existsTrack($track['id'])) {
                $sql = "INSERT INTO `media_tracks` (artist_id, id, track_name, created_at, genre, display_date, track_state, track_format, uri) 
                        VALUES (:artist_id, :id, :track_name, :created_at, :genre, :display_date, :track_state, :track_format, :uri)";
                $this->error['tracks'][] = $track['track_name'] . ' успешно сохранен';
                $query = $this->connect->prepare($sql);
                if ($query->execute($track) == null) {
                    echo 'какая то херня';
                    die();
                }
            } else {
                $this->error['tracks'][] = $track['track_name'] . ' уже есть в базе';
            }
        }
        foreach ($this->error['tracks'] as $track) {
            echo 'Трек ' . $track . PHP_EOL;
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return bool
     */
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

    /**
     * @param int $track
     * @return bool
     */
    private function existsTrack(int $track): bool
    {
        return $this->existsRecords('media_tracks', $track);
    }

    /**
     * @param int $artist
     * @return bool
     */
    private function existsArtist(int $artist): bool
    {
        return $this->existsRecords('media_artists', $artist);
    }
}
