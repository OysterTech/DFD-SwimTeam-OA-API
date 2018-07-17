<?php
/**
 * getProfile 获取用户资料API
 * @author Jerry Cheung
 * @create 2018-06-11
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_POST) && $_POST){
	$openId=$_POST['openId'];
	$profileType=$_POST['profileType'];
	$profile=$_POST['profile'];
	$profile=json_decode($profile,true);

	// 获取用户信息
	$rs=PDOQuery($dbcon,"SELECT a.UserID,a.UserName,a.RealName,a.Password,a.salt,a.Status,a.wxOpenID,b.isAthlete FROM sys_user a,role_list b WHERE a.RoleID=b.RoleID AND a.wxOpenID=?",[$openId],[PDO::PARAM_STR]);
	
	if($rs[1]!=1){
		$ret=returnApiData(4031,"no User");
		die($ret);
	}
	
	$realName=$rs[0][0]['RealName'];
	$UserID=$rs[0][0]['UserID'];
	$isAthlete=$rs[0][0]['isAthlete'];
	$data=$rs[0][0];
	
	// 如果是运动员，获取运动员信息
	if($isAthlete==1 && $profileType=="ath"){
		$sql2="SELECT b.* FROM sys_user a,athlete_list b WHERE a.UserID=b.UserID AND b.UserID=?";
		$rs2=PDOQuery($dbcon,$sql2,[$UserID],[PDO::PARAM_INT]);
		
		if($rs2[1]!=1){
			$ret=returnApiData(4032,"no Athlete");
			die($ret);
		}else{
			$updateRs=PDOQuery($dbcon,"UPDATE athlete_list SET Phone=?,SchoolGrade=?,SchoolClass=?,IDCardType=?,IDCard=? WHERE UserID=?",[$profile['Phone'],$profile['SchoolGrade'],$profile['SchoolClass'],$profile['IDCardType'],$profile['IDCard'],$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

			if($updateRs[1]==1){
				insertLog($dbcon,"小程序-个人","运动员[".$realName."]修改运动员资料",$realName);
				$ret=returnApiData(1,"success");
				die($ret);
			}else{
				$ret=returnApiData(0,"failed");
				die($ret);
			}
		}
	}elseif($profileType=="user"){
		/*$updateRs=PDOQuery($dbcon,"UPDATE sys_user SET UserName=?,Password=?,Salt=? WHERE UserID=?",[$profile['Phone'],$profile['SchoolGrade'],$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

		if($updateRs[1]==1){
			$ret=returnApiData(1,"success",$data);
			die($ret);
		}else{
			$ret=returnApiData(0,"failed");
			die($ret);
		}*/
		$ret=returnApiData(0,"invaild Type");
		die($ret);
	}elseif($profileType=="password"){
		$oldPassword=$profile['oldPassword'];
		$newPassword=$profile['newPassword'];

		$encryptOldPwd=encryptPW($oldPassword,$rs[0][0]['salt']);
		$newSalt=getRanSTR(8);
		$encryptNewPwd=encryptPW($newPassword,$newSalt);

		if($encryptOldPwd==$rs[0][0]['Password']){
			$updateRs=PDOQuery($dbcon,"UPDATE sys_user SET Password=?,salt=? WHERE UserID=?",[$encryptNewPwd,$newSalt,$UserID],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_INT]);

			if($updateRs[1]==1){
				insertLog($dbcon,"小程序-个人","用户[".$realName."]修改密码",$realName);
				$ret=returnApiData(1,"success");
				die($ret);
			}else{
				$ret=returnApiData(0,"failed");
				die($ret);
			}
		}
	}else{
		$ret=returnApiData(0,"invaild Type");
		die($ret);
	}
}
?>
