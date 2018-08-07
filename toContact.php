<?php
/**
 * toContact 留言API
 * @author Jerry Cheung
 * @create 2018-08-07
 * @update 2018-08-07
 */

require_once("db.func.php");
require_once("api.func.php");
require_once("public.func.php");

if(isset($_POST) && $_POST){
	$openId=isset($_POST['openId'])?$_POST['openId']:die(returnApiData(5001,"Param Lack OID"));
	$realName=isset($_POST['realName'])?$_POST['realName']:die(returnApiData(5002,"Param Lack realName"));
	$contactType=isset($_POST['contactType'])?$_POST['contactType']:die(returnApiData(5003,"Param Lack contactType"));
	$contactNum=isset($_POST['contactNum'])?$_POST['contactNum']:die(returnApiData(5004,"Param Lack contactNum"));
	$content=isset($_POST['content'])?$_POST['content']:die(returnApiData(5005,"Param Lack Content"));

	$sql="INSERT INTO wechat_message(open_id,real_name,content,contact_type,contact_num) VALUES (?,?,?,?,?)";
	$rs=PDOQuery($dbcon,$sql,[$openId,$realName,$content,$contactType,$contactNum],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
	
	if($rs[1]==1){
		$ret=returnApiData(1,"success");
	}else{
		$ret=returnApiData(0,"unknown Error",[$_POST,$rs]);	
	}
	
	echo $ret;
}
?>
