<?php

namespace App\Urls;

class SoundCloudUrl
{
    const BASE_SOUND_CLOUD_URL = "https://soundcloud.com/";

    const FIRST_PART_URL = 'https://api-v2.soundcloud.com/users/';

    const SECOND_PART_URL = '/tracks?representation=';

    const THIRD_PART_URL = '&limit=20&offset=0&linked_partitioning=1&app_version=1618476442&app_locale=en%5Bobject%20Object%5D';

    /**
     * @var string
     */
    private $url;

    /**
     * @param string $artist
     * @return string
     */
    public function getArtistBaseUrl(string $artist): string
    {
        $this->url = self::BASE_SOUND_CLOUD_URL . $artist;

        return $this->url;
    }

    /**
     * @param string $param
     * @return string
     */
    public function getFirstCollectionTracksUrl(string $param): string
    {
        $this->url = self::FIRST_PART_URL . $param . self::SECOND_PART_URL . self::THIRD_PART_URL . getenv('CLIENT_ID');

        return $this->url;
    }

    /**
     * @param string $params
     * @return string
     */
    public function getNextCollectionTracksUrl(string $params): string
    {
        $this->url = $params . getenv('CLIENT_ID');

        return $this->url;
    }
}
