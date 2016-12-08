<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('RECHARGE_URL', 'http://api.teegon.com/');
define('CLIENT_ID',     'ilkpnm34sifytz3ttw766cdd');
define('CLIENT_SECRET', 'npaqynbrxtyg5ahjmutpiczk5qz4jtrw');
define('BASE_URL', 'http://welfare.teegon.com/');

class Recharge extends PublicController {
    public function __construct(){
        parent::__construct();
		$this->load->model("RechargeModel", "Recharge", true);
    }
    public function index(){	
		redirect('Recharge/gorecharge?orderId=2000101010101010');
		$this->page('Recharge/index.html');
    }
	public function gorecharge(){	
		//调用天工支付接口 
		$TeegonService 		 = $this->TeegonService();
		$param['order_no']   = '2000101010101010'; //订单号
		$param['channel']    = 'wxpay_jsapi'; //表单提交的方式支持  alipay  wxpay   chinapay_b2c  chinapay  需要哪个就替换哪个
		$param['amount']     = 0.01;//金额
		$param['subject']    = '标题';  //商品名
		$param['metadata']   = "备注";     //备注
		$param['return_url'] = BASE_URL.'/Recharge/RechargeToCallback';  //同步跳转页面    同步通知
		$param['notify_url'] = BASE_URL.'/Recharge/RechargeToCallback';//支付成功后天工支付网关通知   异步通知
		$param['client_ip']  = '127.0.0.1';
		$param['client_id']  = CLIENT_ID;//天工收银开发者 CLIENT_ID
		$signRes = $TeegonService -> sign($param);
		$param['sign'] = $signRes;
		$post  = $TeegonService->post('v1/charge',$param);
		$data = json_decode($post,true);
		if(!$data['result']['action']['params']){
			redirect('Recharge/infoerror');//信息有误 
		}else{
			$js = $data['result']['action']['params'];
			//天工支付go
			echo  $this->getHtml($param, $js);
			exit;
		}
	}
	public function infoerror(){
		echo "信息有误";
		exit;
	}
	//充值成功回调验证信息
	public function RechargeToCallback(){
		 $orderId              =   isset($_GET['order_no']) ?trim($_GET['order_no']):'';	 
		if($_GET['is_success'] == true){					
			 // 所有者openid
			$data['openid']  	  = isset($_GET['buyer'])?trim($_GET['buyer']):'';
			//账号所有人open_id
			$data['buyer_openid'] = isset($_GET['buyer_openid'])?trim($_GET['buyer_openid']):'';
			//=wxpaymp_pinganpay 支付方式
			$data['channel']      = isset($_GET['channel'])?trim($_GET['channel']):'';
			//charge_id=jixlglvg2c7vm2alm4wx4obw 支付订单唯一标识，第三方
			$data['charge_id']    = isset($_GET['charge_id'])?trim($_GET['charge_id']):'';
			//metadata    =%E8%AF%9D%E8%B4%B9%E5%85%85%E5%80%BC		备注
			$data['describes']     = isset($_GET['metadata'])?trim($_GET['metadata']):'支付成功';
			//支付状态 已支付	
			$data['pay_status']    = 2;
			//支付时间
			$data['utime']        = isset($_GET['pay_time'])?trim($_GET['pay_time']):time();
			// 支付单号
			$data['payment_no']   = isset($_GET['payment_no'])?trim($_GET['payment_no']):'';
			$result    = $this->Recharge->sqlStr($data, 'rechaege_orders','update', " where payment_no='' and orderid=".$orderId."");
		 
		}else{
			redirect('Recharge/getOrderInfo?orderId='.$orderId);
		}
	}
	public function getHtml($param,$js){
		return "
		<form action='".RECHARGE_URL."charge/pay' method='post'>
			<input type='hidden' name='order_no'   value='".$param['order_no']."' />
			<input type='hidden' name='channel'    value='".$param['channel']."' />
			<input type='hidden' name='amount'     value='".$param['amount']."' />
			<input type='hidden' name='subject'    value='".$param['subject']."' />
			<input type='hidden' name='metadata'   value='".$param['metadata']."' />
			<input type='hidden' name='client_ip'  value='".$param['client_ip']."' />
			<input type='hidden' name='return_url' value='".$param['return_url']."' />
			<input type='hidden' name='notify_url' value='".$param['notify_url']."' />
			<input type='hidden' name='sign'       value='".$param['sign']."' />
			<input type='hidden' name='client_id'  value='".$param['client_id']."' />
			<input type='hidden' name='channel'    value='".$param['channel']."' />
		</form>
		<script>
		".$js."
		</script>
		";
	}
				
  //天空接口sdk
    public function TeegonService(){
        include_once(APPPATH.'libraries/teegon.php');
        $srv = new TeegonService(RECHARGE_URL);
        return $srv;
    }
    public function TeegonInterface(){
        include_once(APPPATH.'libraries/TeegonInterface.php');
        $srv = new TeegonInterface();
        return $srv;
    } 
	
}