<?php
require_once("./libs/MYSQL_CONNECT.php");
require_once("./tool/utils.php");

header("content-type:application/json;charset=UTF-8");

$sql = "select * from xygg order by id desc limit 20";
$rs = mysql_query($sql);
$arr = array();
while ( $row = mysql_fetch_array($rs) ){
    $sub_arr = array();
    $sub_arr['title'] = $row[1];
    $sub_arr['href'] = $row[2];
    array_push($arr, $sub_arr);
}
echo JSON($arr);
?>
