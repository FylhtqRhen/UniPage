<?php

namespace Helpers\Urls;

use GuzzleHttp\Client;

class SoundCloudUrl implements UrlHelper
{

    const BASE_SOUND_CLOUD_URL = "https://soundcloud.com";
    const FIRST_PART_URL = 'https://api-v2.soundcloud.com/users/';
    const SECOND_PART_URL = '/tracks?representation=&';
    const THIRD_PART_URL = '&limit=20&offset=0&linked_partitioning=1&app_version=1618476442&app_locale=en%5Bobject%20Object%5D';

    private string $url;

    public function getBaseUrl($actor): string
    {
        $this->url = self::BASE_SOUND_CLOUD_URL . $actor;
        return $this->url;
    }

    public function getFirstUrl($param): string
    {
        $this->url = self::FIRST_PART_URL . $param . self::SECOND_PART_URL . self::THIRD_PART_URL . $_ENV['client_id'];
        return $this->url;
    }

    public function getNextUrl($params): string
    {
        $this->url = $params . $_ENV['client_id'];
        return $this->url;
    }
}
