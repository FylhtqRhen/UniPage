<?php

namespace App;

use App\Handles\Exception\ArtistException;
use App\Handles\Exception\TrackException;

class Saver
{
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
        $sql = "INSERT INTO `media_artists` (avatar_url, first_name, followers_count, full_name, id, kind, last_modified, last_name, permalink, permalink_url, uri, urn, username, verified, city, country_code, station_permalink)
                    VALUES (:avatar_url, :first_name, :followers_count, :full_name, :id, :kind, :last_modified, :last_name, :permalink, :permalink_url, :uri, :urn, :username, :verified, :city, :country_code, :station_permalink)
                    ON DUPLICATE KEY UPDATE 
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
        $query = $this->connect->prepare($sql);
        if (!$query->execute($artist)) {
            throw new ArtistException();
        }
    }

    /**
     * @param array $tracks
     */
    public function saveTracks(array $tracks): void
    {
        foreach ($tracks as $track) {
            $sql = "INSERT INTO `media_tracks` (artist_id, id, track_name, created_at, genre, display_date, track_state, track_format, uri) 
                        VALUES (:artist_id, :id, :track_name, :created_at, :genre, :display_date, :track_state, :track_format, :uri)
                        ON DUPLICATE KEY UPDATE 
                        track_name = :track_name,
                        created_at = :created_at,
                        genre = :genre,
                        display_date = :display_date,
                        track_state = :track_state,
                        track_format = :track_format,
                        uri = :uri";
            $query = $this->connect->prepare($sql);
            if (!$query->execute($track)) {
                throw new TrackException();
            }
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
}
