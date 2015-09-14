<?php
header("content-type:text/html; charset=utf-8");
include("./fun.php");
include("./model.php");
include("./libs/MYSQL_CONNECT.php");
$a = select_kb("201422052");
//var_dump($a);
$day=date("Y-m-d",strtotime('+1 day'));
echo $day;

$back = format_kb($a,$day);
if ($back == "") {
    $back = "向学校查询课表时出错，建议重新绑定下";
}
echo $back;

$s = assistor_kb_tomorrow("201422052");
echo $s;


$json_content=json_decode($a);

//$week = strtolower(date("l"));
$week = date("l",strtotime($day));
$week = strtolower($week);
//计算周数
$w=calc_week($day);

//echo $week;
//echo $w;

//echo detail_kb($json_content,$w,$week);


?>
