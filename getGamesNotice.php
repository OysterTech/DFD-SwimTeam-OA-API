<?php
/**
 * getGamesItem 获取比赛通知API
 * @author Jerry Cheung
 * @create 2018-06-22
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_GET['GamesID']) && $_GET['GamesID']>=1){
	$GamesID=$_GET['GamesID'];
	$rs=PDOQuery($dbcon,"SELECT NoticeID,Type,Title FROM games_notice WHERE GamesID=? AND isDelete=0",[$GamesID],[PDO::PARAM_STR]);
	
	if($rs[1]>=1){
		insertLog($dbcon,"小程序-比赛","获取比赛通知|GID:".$GamesID,getIP());
		$ret=returnApiData(1,"success",$rs[0]);
	}else{
		$ret=returnApiData(0,"no Notice");	
	}

	echo $ret;
}
?>
