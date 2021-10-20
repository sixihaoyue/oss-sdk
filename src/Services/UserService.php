<?php

namespace OSS\SDK\Services;

use Illuminate\Support\Facades\Cache;
use OSS\SDK\Entities\OSSUser;
use OSS\SDK\Libs\Client;

class UserService
{
    public function get($userId, $cache = true)
    {
        $cacheUsers = $cache ? Cache::remember('oss-users', 5 * 60, function() {
            // 全量获取一次 User
            $client = app(Client::class);
            $list = $client->get('app/user/list');
            return array_map(function($user) use (&$result) {
              $id = $user['id'];
              $ossUser = new OSSUser($user);
              $result[$id] = $ossUser;
              return $ossUser;
            }, $list);
        }) : [];
        $ids = $userId === null ? [] : (is_string($userId) ? [$userId]: $userId);
        $result = [];
        $newIds = [];
        foreach ($ids as $id) {
          if ($cacheUsers[$id] ?? false) {
            $result[$id] = $cacheUsers[$id];
          } else {
            $newIds[] = $id;
          }
        }
        if (!empty($newIds) || $userId === null) {
          $client = app(Client::class);
          $list = $client->get('app/user/list', ['ids' => $newIds]);
          $users = array_map(function($user) use (&$result) {
            $id = $user['id'];
            $ossUser = new OSSUser($user);
            $result[$id] = $ossUser;
            return $ossUser;
          }, $list);
          $cacheUsers = array_merge($cacheUsers, $users);
        }
        return is_string($userId) ? $result[$userId] : collect($result);
    }

}
