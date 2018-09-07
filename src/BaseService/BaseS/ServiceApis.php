<?php
namespace BaseService\BaseS;

use BaseService\BaseS;

/**
 * 获取服务接口
 * Class ServiceApis
 * @package BaseService\BaseS
 */
class ServiceApis extends BaseS
{
    CONST RESOURCE = 'serversToken';

    public function __construct()
    {
        parent::__construct(self::RESOURCE);
    }


    /**
     * 获取服务接口
     * @return object
     */
    public function checkServersToken($servicestoken)
    {

        $response = $this->get($this->uri,["servicestoken"=>$servicestoken]);
        return $response;
    }

}