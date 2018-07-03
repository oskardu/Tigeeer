<?php
class WechatOauth{
    private $errorMsg;
    private $appMsg;

	public function __construct(){	
		$this->errorMsg = array(
            "20001" => "<h2>配置文件损坏或无法读取，请重新执行intall</h2>",
            "30001" => "<h2>The state does not match. You may be a victim of CSRF.</h2>",
            "50001" => "<h2>可能是服务器无法请求https协议</h2>可能未开启curl支持,请尝试开启curl支持，重启web服务器，如果问题仍未解决，请联系我们"
            );
		$this->appMsg = array(
            "appid" => "wx71b366c4c42507b5",//微信开放平台申请的网站应用的appid和secret
            "secret" => "c887247075e012793c0fc256e4c38fca",
            "callback" => "http://".Yii::app()->params['wechatlogin']//回调地址，一定要加http或者https;
            );
		
	}
			
	public function wechat_login(){

		//-------生成唯一随机串防CSRF攻击
		$state = md5(uniqid(rand(), TRUE));
		$_SESSION['state'] = $state;

		//-------构造请求url
		$callback = urlencode($this->appMsg['callback']);
		$login_url =  "https://open.weixin.qq.com/connect/qrconnect?appid=".$this->appMsg['appid'] ."&redirect_uri=".$callback."&response_type=code&scope=snsapi_login&state=".$state."#wechat_redirect";

		header("Location:$login_url");
	}
		
	public function wechat_callback(){
	    $callbackResult=array();
		$state = $_SESSION['state'];

        //--------验证state防止CSRF攻击
        if($_GET['state'] != $state){
            $this->showError("30001");
        }

        //-------请求参数列表
		if(!empty($_GET['code'])){
			//dosomething
			$code = $_GET['code'];
			$curl = curl_init();
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appMsg['appid']."&secret=".$this->appMsg['secret']."&code=".$code."&grant_type=authorization_code";
			
			curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址	
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查     
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 获取的信息以文件流的形式返回
			$result = curl_exec($curl);
			curl_close($curl);
				
			if( !empty($result)){
				$result_array = json_decode($result,true);
				$callbackResult=$this->getUserInfo($result_array['access_token'],  $result_array['openid']);
// 				$_SESSION['token'] = $result_array['access_token'];
// 				$_SESSION['openid'] = $result_array['openid'];

				//header("location:/user/third?provider=wechat");
				
			}else{
				//header("location:/login");
			}
		}else{
			//header("location: /login");
		}
		return $callbackResult;
	}
	
	
	public function getUserInfo($token,$openid){
	    $result_array=array();
	    if(!empty($token) && !empty($openid)){
	        $curl = curl_init();
	        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid;
	        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 获取的信息以文件流的形式返回
	        $result = curl_exec($curl);
	        curl_close($curl);
	        if( !empty($result)){
	            $result_array = json_decode($result,true);
	        }
	    }
	    return $result_array;
	}
	
	 public function showError($code, $description = '$'){
        echo "<meta charset=\"UTF-8\">";
        if($description == "$"){
            die($this->errorMsg[$code]);
        }else{
            echo "<h3>error:</h3>$code";
            echo "<h3>msg  :</h3>$description";
            exit(); 
        }
    }		
	
}
?>