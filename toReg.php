<?php
/**
 * toReg 注册API
 * @author Jerry Cheung
 * @create 2018-08-07
 * @update 2018-08-07
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_POST) && $_POST){
	$profile=isset($_POST['profile'])?$_POST['profile']:die(returnApiData(2,"Param Lack Profile"));
	$profile=json_decode($profile,true);

	$salt=getRanSTR(8);
	$encryptPwd=encryptPW($profile['password'],$salt);
	
	$userSql="INSERT INTO sys_user(UserName,RealName,Password,salt,RoleID) VALUES (?,?,?,?,'3')";
	$userQuery=PDOQuery($dbcon,$userSql,[$profile['userName'],$profile['RealName'],$encryptPwd,$salt],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);

	if($userQuery[1]==1){
		$userQuery2=PDOQuery($dbcon,"SELECT UserID FROM sys_user WHERE UserName=?",[$profile['userName']],[PDO::PARAM_STR]);
		insertLog($dbcon,"小程序-用户","运动员[".$profile['RealName']."]注册用户[".$profile['userName']."]",$profile['userName']);

		if($userQuery2[1]==1){
			$userId=$userQuery2[0][0]['UserID'];
			$athSql="INSERT INTO athlete_list(UserID,Phone,RealName,SchoolGrade,SchoolClass,Sex,YearGroup,IDCard,IDCardType) VALUES (?,?,?,?,?,?,?,?,?)";
			$athQuery=PDOQuery($dbcon,$athSql,[$userId,$profile['Phone'],$profile['RealName'],$profile['SchoolGrade'],$profile['SchoolClass'],$profile['Sex'],$profile['YearGroup'],$profile['IDCard'],$profile['IDCardType']],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);

			if($athQuery[1]==1){
				insertLog($dbcon,"小程序-运动员","运动员[".$profile['userName']."]注册",$profile['userName']);
				$ret=returnApiData(200,"success");
			}else{
				$ret=returnApiData(3,"regAthleteFailed");
			}
		}else{
			$ret=returnApiData(1,"findUserFailed");
		}
	}else{
		$ret=returnApiData(0,"regUserFailed");
	}
	
	echo $ret;
}

?>