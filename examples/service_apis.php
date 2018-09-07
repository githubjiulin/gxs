<?php

require '../vendor/autoload.php';
use BaseService\BaseS\ServiceApis;
use BaseService\BaseAuthApi;

//用户app_code和app_key
$client = new BaseAuthApi('1804204412', 'guxiansheng2018');

/*print_r($client->getAuth());
exit;*/


$serviceApis = new ServiceApis($client);

$res = $serviceApis->getkeycode();

//echo json_encode((array)$res['body']);exit;


echo '<pre>';
print_r($res);exit;









