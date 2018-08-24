<?php
/**
 * getGamesItem 获取比赛项目API
 * @author Jerry Cheung
 * @create 2018-06-10
 * @update 2018-08-24
 */

require_once("db.func.php");
require_once("api.func.php");

if(isset($_GET['GamesID']) && $_GET['GamesID']>=1 && isset($_GET['YearGroup']) && strlen($_GET['YearGroup'])==4 && $_GET['YearGroup']<date('Y')){
	$userId=$_GET['userId'];
	$gamesInfo=PDOQuery($dbcon,"SELECT AllowUser FROM games_list WHERE GamesID=?",[$_GET['GamesID']],[PDO::PARAM_STR]);

	if($gamesInfo[1]==1){
		if($gamesInfo[0][0]['AllowUser']==""){

		}else{
			$allowUser=$gamesInfo[0][0]['AllowUser'];
			$allowUser=explode(",",$allowUser);
			if(in_array($userId,$allowUser)===false){
				die($ret=returnApiData(2,"noPurview"));
			}
		}
	}else{
		$ret=returnApiData(0,"no Item");
	}

	$rs=PDOQuery($dbcon,"SELECT a.ItemID,a.YearGroup,a.ItemName FROM item_list a,games_item b WHERE b.GamesID=? AND a.YearGroup=? AND b.ItemID=a.ItemID ORDER BY a.YearGroup",[$_GET['GamesID'],$_GET['YearGroup']],[PDO::PARAM_STR,PDO::PARAM_STR]);
	
	if($rs[1]>=1){
		$ret=returnApiData(1,"success",$rs[0]);
	}else{
		$ret=returnApiData(0,"no Item");
	}

	echo $ret;
}
?>
