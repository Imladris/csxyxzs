<?php
include_once("../libs/MYSQL_CONNECT.php");
header("Access-Control-Allow-Origin: *"); 
$sql = "select shitang.id,shitang.name from shitang,cooperation_dk where shitang.id = cooperation_dk.id";
$rs = mysql_query($sql);
$arr = array();
while ( $row = mysql_fetch_array($rs)) {
    $id = $row[0];
    $dk_name = $row[1];
    $sub_sql = "select name,price from caidan where id = $id";
    $sub_rs = mysql_query($sub_sql);
    $caidans = array();
    while ( $sub_row = mysql_fetch_array($sub_rs)) {
        $name=$sub_row[0];
        $price=$sub_row[1];
        $caidan = array(
            'name'=>$name,
            'price'=>$price
        );
        array_push($caidans, $caidan);
    }
    $sub_arr = array(
        'id'=>$id,
        'name'=>$dk_name,
        'caidan'=>$caidans
    );
    array_push($arr, $sub_arr);
}
echo json_encode(array("items"=>$arr));
?>