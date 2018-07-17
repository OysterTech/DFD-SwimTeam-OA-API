<?php
/**
 * public.func 公用函数库
 * @author Jerry Cheung
 * @create 2018-07-17
 */


/**
* ------------------------------
* getIP 获取客户端IP地址
* ------------------------------
* @return String 客户端IP地址
* ------------------------------
**/
function getIP()
{
  if(!empty($_SERVER["HTTP_CLIENT_IP"])){
    $cip = $_SERVER["HTTP_CLIENT_IP"];
  }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
    $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  }elseif(!empty($_SERVER["REMOTE_ADDR"])){
    $cip = $_SERVER["REMOTE_ADDR"];
  }else{
    $cip = "无法获取！";
  }
  
  return $cip;
}


/**
 * encryptPW 加密密码
 * @param 待加密密码
 * @param 盐
 * @return String 加密后密文
 */
function encryptPW($Password,$Salt)
{
  // 加密后的Salt
  $Salt=md5($Salt);

  // 待处理的密文
  $AllPwd=$Password.$Salt;

  // 交换位置
  $a=substr($AllPwd,0,5);
  $b=substr($AllPwd,5,8);
  $c=substr($AllPwd,13,8);
  $d=substr($AllPwd,21,11);
  $e=substr($AllPwd,32);
  $ChangeLoc=$b.$d.$a.$c.$e;

  // 再次组合
  $Data=$Salt.$ChangeLoc;
  $Data=base64_encode($Data);

  // 返回加密后的密文
  return $Data;
}


function getRanSTR($length,$LettersType=2)
{
	if($LettersType==0){
		$str="ZXCVBNQWERTYASDFGHJKLUPM";
	}elseif($LettersType==1){
		$str="qwertyasdfghzxcvbnupmjk";
	}else{
		$str="qwertyZXCVBNasdfghQWERTYzxcvbnASDFGHupJKLnmUPjk";
	}

	$ranstr="";
	$strlen=strlen($str)-1;
	for($i=1;$i<=$length;$i++){
		$ran=mt_rand(0,$strlen);
		$ranstr.=$str[$ran];
	}

	return $ranstr;
}


function insertLog($dbcon,$type,$content,$user){
	PDOQuery($dbcon,"INSERT INTO sys_log(LogType,LogContent,LogUser,LogIP) VALUES (?,?,?,?)",[$type,$content,$user,getIP()],[PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
}