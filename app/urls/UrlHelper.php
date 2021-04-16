<?php

namespace Helpers\Urls;

interface UrlHelper
{
    public function getBaseUrl($actor);

    public function getFirstUrl($param);

    public function getNextUrl($params);

}
