<?php
/**
 * getAccessToken 获取AccessToken的API
 * @author Jerry Cheung
 * @create 2018-06-09
 */

require_once("db.func.php");
require_once("api.func.php");
include("config.php");

// @TODO 加些验证(如POST->AppID校验)
$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.APPID.'&secret='.SECRET;
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
$output=curl_exec($ch);
curl_close($ch);

// 处理获取到的数据
$data=json_decode($output);

$ret=returnApiData(1,"success",$data);
echo $ret;

?>
