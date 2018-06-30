<?php
/**
 * sendTemplateMessage 发送模板消息API
 * @author Jerry Cheung
 * @create 2018-06-23
 */

require_once("db.func.php");
require_once("api.func.php");

if(isset($_POST) && $_POST){
	$accessToken=$_POST['accessToken'];
	$openId=$_POST['openId'];
	$templateId=$_POST['templateId'];
	$page=isset($_POST['page'])?$_POST['page']:'/pages/games/index';
	$formId=$_POST['formId'];
	$data=$_POST['data'];

	$data=json_decode($data,TRUE);
	$postData=array('touser'=>$openId,'template_id'=>$templateId,'page'=>$page,'form_id'=>$formId,'data'=>$data);
	$postData=json_encode($postData,JSON_UNESCAPED_UNICODE);
	
	$url='https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$accessToken;
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	$output=curl_exec($ch);
	curl_close($ch);
	
	// 处理获取到的数据
	$data=json_decode($output,TRUE);

	$ret=returnApiData(1,"success",$output);
	echo $ret;
}
?>
