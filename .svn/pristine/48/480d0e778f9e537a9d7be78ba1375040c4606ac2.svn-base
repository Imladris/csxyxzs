<?php
require_once("../libs/MYSQL_CONNECT.php");
$sql = "select id,pw from id_pw";
$rs = mysql_query($sql);
while (	$row = mysql_fetch_array($rs)) {
	$id = $row[0];
	$result = mysql_query("select count(*) from id_name where id='$id'");
	$t = mysql_fetch_array($result);
	$cnt = $t[0];
	if($cnt!=0){
		continue;
	}
	$pw = $row[1];
	$url = "http://1.csxyxzs1.sinaapp.com/verify?name=$id&password=$pw";
	$json_page = file_get_contents($url);
	$json = json_decode($json_page,true);	
	//echo var_dump($json);
	$name = $json["name"];
	mysql_query("insert into id_name(id,name) values('$id','$name')");
}

?>