<?php

namespace OSS\SDK;

use OSS\SDK\Libs\Client;
use OSS\SDK\Services\UserService;
use OSS\SDK\Services\SearchService;

class Service
{

    public static function user()
    {
        return new UserService();
    }

    public static function search()
    {
      return new SearchService();
    }
}
