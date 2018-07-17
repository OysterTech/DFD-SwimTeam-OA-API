<?php
/**
 * getProfile 获取用户资料API
 * @author Jerry Cheung
 * @create 2018-06-11
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_GET) && $_GET){
	$openID=$_GET['openID'];
	
	// 获取用户信息
	$rs=PDOQuery($dbcon,"SELECT a.UserID,a.UserName,a.RealName,a.Status,a.wxOpenID,b.isAthlete FROM sys_user a,role_list b WHERE a.RoleID=b.RoleID AND a.wxOpenID=?",[$openID],[PDO::PARAM_STR]);
	
	if($rs[1]!=1){
		$ret=returnApiData(4031,"User Not Found");
		die($ret);
	}
	
	$UserID=$rs[0][0]['UserID'];
	$realName=$rs[0][0]['RealName'];
	$userName=$rs[0][0]['UserName'];
	$isAthlete=$rs[0][0]['isAthlete'];
	$data=$rs[0][0];
	
	// 如果是运动员，获取运动员信息
	if($isAthlete==1){
		$sql2="SELECT b.* FROM sys_user a,athlete_list b WHERE a.UserID=b.UserID AND b.UserID=?";
		$rs2=PDOQuery($dbcon,$sql2,[$UserID],[PDO::PARAM_INT]);
		
		if($rs2[1]!=1){
			$ret=returnApiData(4032,"Athlete Not Found");
			die($ret);
		}
		
		$data=array_merge($data,$rs2[0][0]);
	}
	
	// 返回数据
	$rs3=PDOQuery($dbcon,"UPDATE sys_user SET LastDate=? WHERE UserID=?",[date("Y-m-d H:i:s"),$UserID],[PDO::PARAM_STR,PDO::PARAM_INT]);
	insertLog($dbcon,"小程序-用户","[".$realName."]授权登录",$userName);
	$ret=returnApiData(1,"success",$data);
	die($ret);
}
?>
