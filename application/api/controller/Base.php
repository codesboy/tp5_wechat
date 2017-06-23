<?php
namespace app\api\controller;
use think\Controller;

/**
* 微信接入 验证服务器的有效性
*/
class Base extends Controller
{

    function __construct(argument)
    {
        # code...
    }

    // 验证第三方服务器权限 URL就是自己服务器的域名，要备案，或者使用新浪sae
    public function index(){

        // 获得微信公众平台以get方式请求我们的URL地址所传的四个参数
        /*signature   微信加密签名，signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
        timestamp   时间戳
        nonce   随机数
        echostr 随机字符串*/
       $nonce=input('get.nonce');
       $timestamp=input('get.timestamp');
       $echostr=input('get.echostr');
       $signature=input('get.signature');

       // 自定义一个token令牌
       $token=config('api.token');

       // 将token、timestamp、nonce三个参数进行字典序排序形成数组 然后字典序排序
       $array=array($nonce,$timestamp,$token);
       sort($array,SORT_STRING);

       // 拼接(implode/join)成字符串  然后sha1加密  加密后的字符串可与signature对比,标识该请求来源于微信
       $str=sha1(implode($array));
       if($str==$signature && $echostr){
            // 第一次接入weixin api接口的时候（在后台配置的时候）,微信服务器所请求的URL参数中有$echostr参数，第二次就没有了，在微信公众平台填写服务器配置并提交的时候就是第一次接入
            echo $echostr;
            exit;
       }else{//如果没有$echostr参数，就表示第二次以后接入
            $this->responseMsg();
       }
    }
}
