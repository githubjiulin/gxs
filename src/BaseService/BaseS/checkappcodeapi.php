<?php
namespace BaseService\BaseS;

use BaseService\BaseS;

/**
 * 获取服务接口
 * Class ServiceApis
 * @package BaseService\BaseS
 */
class checkappcodeapi extends BaseS
{
    CONST RESOURCE = 'appcodeapi';

    public function __construct()
    {
        parent::__construct(self::RESOURCE);
    }


    /**
     * 获取服务接口
     * @return object
     */
    public function checkappcodeapi($appcode,$url,$method)
    {

        $response = $this->get($this->uri,["app_code"=>$appcode,"url"=>$url,"method"=>$method]);
        return $response;
    }

}