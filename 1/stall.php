<?php
require_once("./templates/schedule_tpl.php");
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");

//获取对应id档口菜单
//stall.php?id=
//获取档口
//stall.php?getStall=1

$getStall = $_GET['getStall']?$_GET['getStall']:'';
// receive user_id
$id = $_GET['id']?$_GET['id']:'';
if ($id == '' && $getStall== '') {
    exit;
}


if ($getStall == '1') {//返回档口
    $sql = "SELECT * FROM shitang";
} else {//返回菜单
    $sql = "SELECT * FROM caidan WHERE id = '$id'";
}

$result = mysql_query($sql);

$first = 1;
echo '{"data":[';
while($back=mysql_fetch_array($result))   //遍历结果集
{
    if ($first == 1) {
        $first = 0;
    } else {
        echo ',';
    }
    echo json_encode($back);
}
echo ']}';

exit;
?>





















