<?php
/**
 * getGamesItem 获取通知API
 * @author Jerry Cheung
 * @create 2018-06-22
 */

require_once("db.func.php");
require_once("api.func.php");

if(isset($_GET['NoticeID']) && $_GET['NoticeID']>=1){
	$rs=PDOQuery($dbcon,"SELECT Type,Title,Content,FileJSON,PageView FROM games_notice WHERE NoticeID=? AND isDelete=0",[$_GET['NoticeID']],[PDO::PARAM_STR]);
	
	if($rs[1]==1){
		$ret=returnApiData(1,"success",$rs[0][0]);
	}else{
		$ret=returnApiData(0,"no Notice");	
	}
}

echo $ret;
?>
