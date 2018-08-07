<?php
/**
 * getGamesItem 获取通知API
 * @author Jerry Cheung
 * @create 2018-06-22
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_GET['NoticeID']) && $_GET['NoticeID']>=1){
	$rs=PDOQuery($dbcon,"SELECT Type,Title,Content,FileJSON,PageView FROM games_notice WHERE NoticeID=? AND isDelete=0",[$_GET['NoticeID']],[PDO::PARAM_STR]);
	
	if($rs[1]==1){
		$rs2=PDOQuery($dbcon,"UPDATE games_notice SET PageView=PageView+1
		 WHERE NoticeID=? AND isDelete=0",[$_GET['NoticeID']],[PDO::PARAM_STR]);
		insertLog($dbcon,"小程序-比赛","获取比赛通知详情|NID:".$_GET['NoticeID'],getIP());
		$ret=returnApiData(1,"success",$rs[0][0]);
	}else{
		$ret=returnApiData(0,"no Notice");
	}

	echo $ret;
}
?>
