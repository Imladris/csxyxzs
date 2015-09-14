<?php 
include_once("fun.php");
require("./MYSQL_CONNECT.php");
//test
// fix_k('oRSMXuJJLuY-Tdvf304HKS2_Eczg');
// fix_s('oRSMXuJJLuY-Tdvf304HKS2_Eczg');
echo "<h1>Start!</h1><br/>";
echo "<h1>score:</h1><br/>";
$sql = "select openid from chengji where score like '%DOCTYPE%' ";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
	echo "updating score where openid = ".$value[0]."<br/>";
	fix_s($value[0]);
}

echo "<h1>kebiao:\n</h1>";
$sql = "select openid from kebiao where ke_biao like '%DOCTYPE%' ";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
	echo "updating kebiao where openid = ".$value[0]."<br/>";
	fix_k($value[0]);
}

echo "<h1>Over!</h1>";

function fix_s($openid){
	$sql = "select id,pw from user where OpenId = '$openid' ";
	$query = mysql_query($sql);
	$value = mysql_fetch_array($query);

	$id = $value[0];
	$pw = $value[1];
	$json_content = get_score_json($id,$pw);
	
	if (strstr($json_content,"DOCTYPE")) {
		echo "failed<br/>";
	}
	else
	{
		$sql = "update chengji set score='$json_content' where openid = '$openid'";
		mysql_query($sql);
		echo "success<br/>";
	}
}

function fix_k($openid){
	$sql = "select id,pw from user where OpenId = '$openid' ";
	$query = mysql_query($sql);
	$value = mysql_fetch_array($query);

	$id = $value[0];
	$pw = $value[1];
	$json_content = get_score_json($id,$pw);
	if (strstr($json_content,"DOCTYPE")) {
		echo "failed<br/>";
	}
	else
	{
		$sql = "update kebiao set ke_biao='$json_content' where openid = '$openid'";
		mysql_query($sql);
		echo "success<br/>";
	}
}

?>