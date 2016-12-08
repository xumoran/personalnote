<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin {

    var $wxUrl = "https://api.weixin.qq.com/cgi-bin/";
    var $tokenCreated = 0;

    public function notify( $openid , $templateid , $data ,$order_no, $appid = FULI_APPID, $secret = FULI_SECRET){
        $buy_or_get = ($appid == FULI_APPID) ? "wx" : "buysuc";
		$config =& get_config(); 
		$this->recharge_base_url = 'http://test.teegon.ishopex.cn/recharge/';//$config['base_url'];
        $postData = array(
            "touser" => $openid, 
            "template_id" => $templateid ,
            "url" => $this->recharge_base_url.'/Recharge/getOrderInfo'."?orderId=$order_no",
            "data"=> $data,
        );
        return $this->api( "message/template/send" , json_encode($postData), $appid, $secret);
    }

    public function api( $method ,  $params, $appid, $secret) {	 
        $u = $this->wxUrl.$method."?access_token=".$this->getAccEssToken($appid, $secret);
        return $this->httpPost( $u , $params );
    }

    public function refreshAccessToken($appid, $secret){
        $re = $this->httpGet( "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret );
        $re1 = json_decode( $re , 1 );
        $this->accessToken = $re1["access_token"];
        $CI = get_instance();    
		$CI->load->model("RechargeModel", "master_domain", true);
       if($appid == FULI_APPID){
			 $data  = $CI->Recharge->getInfo('wx_access_token'," id=1 " , "id", '*', 'asc', 'row_array');
		}else{
			$data  = $CI->Recharge->getInfo('wx_access_token'," id=2 " , "id", '*', 'asc', 'row_array');
		}

        return $this->accessToken;
    }
    public function getAccEssToken($appid, $secret) {
        if ( ! $this->accessToken ){
            $CI = get_instance();
			$CI->load->model("RechargeModel", "Recharge", true);
            if($appid == FULI_APPID){
				 $data  = $CI->Recharge->getInfo('wx_access_token'," id=1 " , "id", '*', 'asc', 'row_array');
            }else{
                $data  = $CI->Recharge->getInfo('wx_access_token'," id=2 " , "id", '*', 'asc', 'row_array');
            }

            if( time() - $data->created > 600 || !$data->token ){
                $this->refreshAccessToken($appid, $secret);
            }else{
                $this->accessToken = $data->token;
            }
        }
        return $this->accessToken;
    }

    public function httpGet( $url ) {
        return file_get_contents($url);
    }

    public function httpPost( $url , $p ) {
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $p,
                'timeout' => 60 
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }




}

