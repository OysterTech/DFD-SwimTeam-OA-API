<?php
/**
 * toEnroll 报名API
 * @author Jerry Cheung
 * @create 2018-06-11
 * @update 2018-06-23
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_POST) && $_POST){
	$successCount=0;
	$AthID=isset($_POST['athId'])?$_POST['athId']:die(returnApiData(5001,"Param Lack AID"));
	$GamesID=isset($_POST['gamesId'])?$_POST['gamesId']:die(returnApiData(5002,"Param Lack GID"));
	$ItemIDs=isset($_POST['itemIds'])?$_POST['itemIds']:die(returnApiData(5002,"Param Lack IID"));
	
	$ItemIDs=explode(",",$ItemIDs);
	
	foreach($ItemIDs as $ItemID){
		$rs=PDOQuery($dbcon,"INSERT INTO enroll_item(AthID,GamesID,ItemID) VALUES (?,?,?)",[$AthID,$GamesID,$ItemID],[PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_INT]);
		$successCount+=$rs[1];
	}
	
	if($successCount>=1){
		insertLog($dbcon,"小程序-比赛","运动员[".$AthID."]报名比赛[".$GamesID."]",getIP());
		$ret=returnApiData(1,"success");
	}else{
		$ret=returnApiData(0,"unknown Error");	
	}
	
	echo $ret;
}
?>
