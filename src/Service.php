<?php

namespace OSS\SDK;

use OSS\SDK\Libs\Client;
use OSS\SDK\Services\UserService;

class Service
{

  public static function user()
    {
        return new UserService();
    }

}
