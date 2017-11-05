<?php
define("UC_SERVER","http://filmfly.sinaapp.com/rest/");
define("UC_ADMIN_ACCOUNT","admin888");
define("UC_ADMIN_PSW","123123");

class httpRequest{
	public function __construct(){
	}
	static function post($url, $post = null,$header=null){
		$ch = curl_init();
		$header = $header or array();
		curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST,1);
        $postStr = "";
        foreach($post as $k=>$v){
            $postStr = $postStr."&$k=".urlencode($v);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postStr);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if($header)curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$result = curl_exec($ch);
		curl_close($ch) ;
		return $result;
	}
	static function postJson($url, $arr = null,$header=null){
		$ch = curl_init();
		$header = $header or array();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		$postStr = json_encode($arr);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postStr);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		if($header)curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$result = curl_exec($ch);
		curl_close($ch) ;
		return $result;
	}
	static function put($url, $post = null,$header=null){
		$ch = curl_init();
		$header = $header or array();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式
		if(!$header)$header = array();
		array_push($header,"X-HTTP-Method-Override: PUT");
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$result = curl_exec($ch);
		curl_close($ch) ;
		return $result;
	}
	static function get($url,$header=null){
		$ch = curl_init($url);
		$header = $header or array();
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );//返回
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		if($header)curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$result = curl_exec($ch);
		curl_close($ch) ;
		return $result;
	}
	static function postFile($url,$filePath){
		$ch = curl_init();
		//加@符号curl就会把它当成是文件上传处理
		$data = array(
			'userfaceimg'=>'@'. $filePath,
			"seskey"=>$_SESSION["user"]["sesKey"],
			"userid"=>$_SESSION["user"]["userId"]
		);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$result = curl_exec($ch);
		curl_close($ch);	
		return $result;
	}
}
?>