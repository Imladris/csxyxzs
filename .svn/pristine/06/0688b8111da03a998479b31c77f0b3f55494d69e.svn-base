<?php
require_once("./templates/schedule_tpl.php");
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");

//获取对应id教师

$id = $_GET['id']?$_GET['id']:'';
if ($id == '') {
    exit;
}

$sql = "SELECT * FROM teacher_name WHERE id = '$id' limit 1";

$result = mysql_query($sql);
$is_empty = 1;

if($back=mysql_fetch_array($result))   //遍历结果集
{
    $is_empty = 0;
}


if ($is_empty == 0) {
    //$back["status"] = "success";
} else {
    //$back["status"] = "failed";
    $back["name"] = "未知";
}
ec
$back["status"] = "success";

echo json_encode($back);

exit;
?>





















