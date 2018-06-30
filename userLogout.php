<?php
/**
 * userLogin 用户登出API
 * @author Jerry Cheung
 * @create 2018-06-30
 */

require_once("db.func.php");
require_once("api.func.php");

if(isset($_POST) && $_POST){
	$openId=isset($_POST['openId'])?$_POST['openId']:die(returnApiData(5001,"Param Lack OID"));
	
	$rs=PDOQuery($dbcon,"SELECT * FROM sys_user WHERE wxOpenID=?",[$openId],[PDO::PARAM_STR]);
	
	// 判断是否存在此用户
	if($rs[1]==1){
		// 解绑微信
		$userId=$rs[0][0]['UserID'];
		$bindWx=PDOQuery($dbcon,"UPDATE sys_user SET wxOpenID=null WHERE UserID=?",[$userId],[PDO::PARAM_INT]);

		if($bindWx[1]==1){
			$ret=returnApiData(1,"success");
		}else{
			$ret=returnApiData(2,"unbind Wechat Failed");
		}
	}else{
		$ret=returnApiData(0,"no User");	
	}
	
	echo $ret;
}
?>
