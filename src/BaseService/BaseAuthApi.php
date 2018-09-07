<?php

namespace BaseService;

/**
* 
*/
class BaseAuthApi
{
    private $username;
    private $password;    
    private $authorization;

    /**
     *  初始化认证信息
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->authorization = ['Authorization' => 'Basic ' . base64_encode($this->username.':'.$this->password)];
    }

    /**
     * 获取认证加密串
     */
    public function getAuth() {
        return $this->authorization;
    }
}