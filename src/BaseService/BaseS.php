<?php
namespace BaseService;

use BaseService\Exceptions\InvalidArgumentException;
use \Httpful\Request;

class BaseS {
    //请求域名
    
    Const BASE_URI = 'serverrest.api.guxiansheng.cn/';

    //Const BASE_URI = 'baseserver.guxiansheng.cn/';
    protected $uri;
   

    /**
     * 构造函数初始化请求路径
     */
    public function __construct( $resource)
    {
        $this->uri = self::BASE_URI.$resource;

    }

    /**
     * GET（SELECT）
     * @param string $uri 请求资源
     * @param string $mime 期望数据返回类型
     * @param array $body 请求body数据
     * @param string $mime 返回数据类型
     * @return object
     */
    public function get($uri, array $body = [], $mime = 'json')
    {
         //debug code
        if(isset($_GET["apidebug"])&&$_GET["apidebug"]){
            $apidebugStr = is_array($body) ? http_build_query($body) : $body;
            header('apidebug-api'.uniqid().':'.$uri.",".$apidebugStr);
        }
        if(!empty($body)){
            $query_string = http_build_query($body);
            $uri .= '?'.$query_string;
        }

        $request = Request::get($uri, $mime);

        $response = $this->requestSend($request);

        return $response;
    }

    /**
     * POST（CREATE）
     * @param string $uri 请求资源
     * @param array|null $body body数据
     * @param string $mime 期望返回数据类型
     * @return object
     */
    public function post($uri, array $body = null, $mime = 'json')
    {
         //debug code
        if(isset($_GET["apidebug"])&&$_GET["apidebug"]){
            $apidebugStr = is_array($body) ? http_build_query($body) : $body;
            header('apidebug-api'.uniqid().':'.$uri.",".$apidebugStr);
        }
        $request = Request::post($uri, $body, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * PUT（UPDATE）
     * @param string $uri 请求资源地址
     * @param array|null $body
     * @param string $mime
     * @return object
     */
    public function put($uri, array $body = null, $mime = 'json')
    {
         //debug code
        if(isset($_GET["apidebug"])&&$_GET["apidebug"]){
            $apidebugStr = is_array($body) ? http_build_query($body) : $body;
            header('apidebug-api'.uniqid().':'.$uri.",".$apidebugStr);
        }
        $request = Request::put($uri, $body, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * DELETE（DELETE）
     * @param string $uri 请求资源地址
     * @param string $mime 期望返回数据类型
     * @return object
     */
    public function delete($uri, $mime = 'json')
    {
         //debug code
        if(isset($_GET["apidebug"])&&$_GET["apidebug"]){           
            header('apidebug-api'.uniqid().':'.$uri);
        }
        $request = Request::delete($uri, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * 统一send出口，公共请求头信息添加
     * @param object $request Request
     * @return object
     */
    private function requestSend($request)
    {

       // $request->addHeaders($this->client->getAuth());
        $response = $request->send();
        $result = $this->processResp($response);
        return $result;
    }

    /**
     * 请求返回处理
     * @param object $response 响应信息
     * @return array
     * @throws APIRequestException
     * @throws ServiceNotAvaliable
     */
    public function processResp($response) {
        $data = $response->body;
 // var_dump($response);exit;
        if ($response->code === 200) {
            $result = [];
            $result['body'] =json_decode($response->raw_body, true);
            $result['http_code'] = $response->code;
            $result['headers'] = $response->headers;
            return $result;
        } elseif (is_null($data)) {
            throw new ServiceNotAvaliable($response);
        } else {
            throw new APIRequestException($response);
        }
    }

    /**
     * ID检验
     * @param $msg_id
     * @throws InvalidArgumentException
     */
    protected function _msgIdParamCheck($msg_id)
    {
        if(!$msg_id || !is_numeric($msg_id)){
            throw new InvalidArgumentException('msg_id must be a number!');
        }
    }

    /**
     * 数组检验
     * @param $data
     * @throws InvalidArgumentException
     */
    protected function _arrayParamCheck($data)
    {
        if(!$data || !is_array($data)){
            throw new InvalidArgumentException('data it must be a an array!');
        }
    }

    /**
     * 接收用户 参数检验以及组装
     * @param int $target_type 接收用户类型 1单聊 2群聊
     * @param int $target_id   接收id
     * @throws InvalidArgumentException
     */
    protected function _targetParamCheck($target_type,$target_id)
    {
        if(!isset($target_type) || !in_array($target_type, [1,2])){
            throw new InvalidArgumentException('target_type must in (1,2)!');
        }
        if(!isset($target_id) || !is_numeric($target_id)){
            throw new InvalidArgumentException('target_id must be set or is numeric!');
        }
    }

    /**
     * 消息参数检验
     * @param string $type 消息类型检验
     * @param array $body 消息内容检验
     * @throws InvalidArgumentException
     */
    protected function _bodyParamCheck($type,$body)
    {
        if(empty($body) && !is_array($body)){
            throw new InvalidArgumentException("send body must be set!");
        }
        if(!isset($type) && !in_array($type, ['text', 'voice', 'image', 'custom'])){
            throw new InvalidArgumentException('key must in (text,voice,image,custom)!');
        }
        if($type == 'text' && empty($body['text'])){
            throw new InvalidArgumentException('send content(text) is empty!');
        }
        if($type == 'voice' && (empty($body['media_id']) || empty($body['media_crc32']) || empty($body['duration']))){
            throw new InvalidArgumentException('send content(voice) lack body param!');
        }
        if($type == 'image' && (empty($body['media_id']) || empty($body['media_crc32']) || empty($body['width']) || empty($body['height']) || empty($body['format']) || empty($body['fsize']))){
            throw new InvalidArgumentException('send content(image) lack body param!');
        }
    }
}
