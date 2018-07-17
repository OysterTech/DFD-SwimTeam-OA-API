<?php
/**
 * getGamesEnrollItem 获取当场比赛报名项目API
 * @author Jerry Cheung
 * @create 2018-06-09
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_POST) && $_POST){
	$AthID=isset($_POST['athId'])?$_POST['athId']:die(returnApiData(5001,"Param Lack AID"));
	$GamesID=isset($_POST['gamesId'])?$_POST['gamesId']:die(returnApiData(5002,"Param Lack GID"));
	
	$rs=PDOQuery($dbcon,"SELECT b.* FROM enroll_item a,item_list b WHERE a.ItemID=b.ItemID AND a.AthID=? AND a.GamesID=?",[$AthID,$GamesID],[PDO::PARAM_STR,PDO::PARAM_STR]);
	
	if($rs[1]>=1){
		insertLog($dbcon,"小程序-比赛","运动员[".$AthID."]获取比赛[".$GamesID."]的项目",getIP());
		$ret=returnApiData(1,"success",$rs[0]);
	}else{
		$ret=returnApiData(0,"no Item");	
	}
	
	die($ret);
}
?>
