<?php
/**
 * Created by IntelliJ IDEA.
 * User: imo
 * Date: 2016/10/20
 * Time: 16:02
 * 用户中心sdk
 */

require_once(__DIR__ . '/teegon_open/src/TeegonClient.php');

class TeegonInterface
{
    public $base_url;
    public $teegon_clinet = null;

    function __construct()
    {
        $this->base_url = USER_CENTER_URL;
        if (is_null($this->teegon_clinet)) {
            $this->teegon_clinet = new TeegonClient($url = TEEGON_OPEN_URL, $key = TEEGON_OPEN_KEY, $secret = TEEGON_OPEN_SECRET);
        }
    }


    function call($method, $path, $params = array())
    {
        $stratTime   = microtime(true);
        if (strtolower($method) == 'post') {
            $result = $this->teegon_clinet->post($path, $params);
        } else {
            $result = $this->teegon_clinet->get($path, $params);
        }

        $result = json_decode($result, true);
        $endTime    = microtime(true);
        $runtime    = ($endTime - $stratTime) * 1000; //将时间转换为毫秒
        log_message("info","usercenter call, runtime:{$runtime}, method:{$method}, path:{$path}, params:".json_encode($params));
        return $result;

//        $url = $this->base_url."/".$path;
//
//        $options = array(
//            CURLOPT_HEADER => 0,
//            CURLOPT_URL => $url,
//            CURLOPT_FRESH_CONNECT => 1,
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_FORBID_REUSE => 1,
//            CURLOPT_TIMEOUT => 30,
//        );
//        $params['sign'] = $this->get_sign($params);
//        $param_string = http_build_query($params);
//        switch(strtolower($method)){
//            case 'post':
//                $options += array(CURLOPT_POST => 1,
//                    CURLOPT_POSTFIELDS => $param_string);
//                break;
//            case 'put':
//                $options += array(CURLOPT_PUT => 1,
//                    CURLOPT_POSTFIELDS => $param_string);
//                break;
//            case 'delete':
//                $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
//                if($param_string)
//                    $options[CURLOPT_URL] .= '?'.$param_string;
//                break;
//            default:
//                if($param_string)
//                    $options[CURLOPT_URL] .= '?'.$param_string;
//        }
//        $ch = curl_init();
//        curl_setopt_array($ch, $options);
//        if( ! $result = curl_exec($ch))
//        {
//            return array('ecode'=>500,'emsg'=>"服务器连接失败");
//        }
//        curl_close($ch);
//
//        $result = json_decode($result,true);
//        return $result;
    }


//    public function get_sign($para_temp)
//    {
//        //除去待签名参数数组中的空值和签名参数
//        $para_filter = $this->para_filter($para_temp);
//        //对待签名参数数组排序
//        $para_sort = $this->arg_sort($para_filter);
//        //生成加密字符串
//        $prestr = $this->create_string($para_sort);
//
//        $isSgin = $this->md5_verify($prestr, USER_CENTER_SECRET);
//
//        return $isSgin;
//    }
//
//
//    private function para_filter($para)
//    {
//        $para_filter = array();
//        reset($para);
//        while (list ($key, $val) = each($para)) {
//            if ($key == "sign") continue;
//            else    $para_filter[$key] = $para[$key];
//        }
//        return $para_filter;
//    }
//
//    private function arg_sort($para)
//    {
//        ksort($para);
//        reset($para);
//        return $para;
//    }
//
//    private function create_string($para)
//    {
//        $arg = "";
//
//        foreach ($para as $key => $val) {
//            $arg .= $key . $val;
//        }
//
//        //如果存在转义字符，那么去掉转义
//        if (get_magic_quotes_gpc()) {
//            $arg = stripslashes($arg);
//        }
//
//        return $arg;
//    }
//
//    private function md5_verify($prestr, $key)
//    {
//        $prestr = $key . $prestr . $key;
//        return strtoupper(md5($prestr));
//    }


}