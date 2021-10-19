<?php

namespace OSS\SDK\Entities;

use Exception;

class OSSUser
{
    protected $_info;

    protected $_appLevel = 0;
    protected $_userData = [];

    public $user_id = '';

    public function __construct($info)
    {
        $this->_info = $info;
        $this->user_id = $info['id'];
        $this->_appLevel = $info['level'] ?? 0;
        $this->_userData = array_values($info['app_data'])[0] ?? [];
    }

    /* 鉴权 */
    public function access($level = 0)
    {
        return $this->_appLevel >= $level;
    }

    public function canAccess()
    {

      return $this->access(1);
    }

    /**
    * 判断是否应用 admin
    * @param string $appId 应用 ID
    * @return boolean
    */
    public function isAdmin()
    {
        return $this->access(7);
    }

    /**
     * 检查用户在 App 中是否包含 role 角色
     * @param Array<string>|string $role 角色列表
     * @return boolean
     */
    public function hasRoles($role)
    {
      return in_array($role, $this->_userData ?? []);
    }
}
