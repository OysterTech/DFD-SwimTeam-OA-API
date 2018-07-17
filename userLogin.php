<?php
/**
 * userLogin 用户登录API
 * @author Jerry Cheung
 * @create 2018-06-24
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_POST) && $_POST){
	$openId=isset($_POST['openId'])?$_POST['openId']:die(returnApiData(5001,"Param Lack OID"));
	$userName=isset($_POST['userName'])?$_POST['userName']:die(returnApiData(5002,"Param Lack UN"));
	$password=isset($_POST['password'])?$_POST['password']:die(returnApiData(5002,"Param Lack Pwd"));
	
	$rs=PDOQuery($dbcon,"SELECT * FROM sys_user WHERE UserName=?",[$userName],[PDO::PARAM_STR]);
	
	// 判断是否存在此用户
	if($rs[1]==1){
		$realName=$rs[0][0]['RealName'];
		$encryptPwd=encryptPW($password,$rs[0][0]['salt']);
		$UserID=$rs[0][0]['UserID'];
		
		// 判断密码有效性
		if($encryptPwd==$rs[0][0]['Password']){
			// 绑定微信
			$bindWx=PDOQuery($dbcon,"UPDATE sys_user SET wxOpenID=?,LastDate=? WHERE UserID=?",[$openId,date("Y-m-d H:i:s"),$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);
			
			if($bindWx[1]==1){
				insertLog($dbcon,"小程序-用户","[".$realName."]小程序帐密登录",$userName);
				$ret=returnApiData(1,"success");
			}else{
				$ret=returnApiData(2,"bind Wechat Failed");
			}
		}else{
			$ret=returnApiData(403,"invaild Pwd");
		}
	}else{
		$ret=returnApiData(0,"no User");	
	}
	
	echo $ret;
}
?>
