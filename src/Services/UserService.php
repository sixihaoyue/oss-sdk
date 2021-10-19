<?php

namespace OSS\SDK\Services;

use OSS\SDK\Entities\OSSUser;
use OSS\SDK\Libs\Client;

class UserService
{
    public function get($userId)
    {
        $client = app(Client::class);
        $info = $client->get('app/user/info', ['id' => $userId]);
        return new OSSUser($info);
    }
}
