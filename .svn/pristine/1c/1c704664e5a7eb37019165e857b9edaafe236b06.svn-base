<?php
require_once("./libs/MYSQL_CONNECT.php");
require_once("./tool/utils.php");
require_once("./config.php");
header("Access-Control-Allow-Origin: *");
header("content-type:application/json;charset=UTF-8");

$p = $_GET['p']?$_GET['p']:1;
$offset = ($p-1)*$pagesize;
$book_name = mysql_real_escape_string($_GET['q']);
$type = $_GET['t']?$_GET['t']:'book';
if($type!='book' && $type!='author'){
    exit();
}

switch ($type) {
case 'book':
    $query_sql = "SELECT * FROM library where upper(booktitle) like upper('%".$book_name."%') LIMIT  $offset , $pagesize";
    $count_sql = "SELECT count(*) FROM library where upper(booktitle) like upper('%".$book_name."%')";
    break;
case 'author':
    $query_sql = "SELECT * FROM library where upper(author) like upper('%".$book_name."%') LIMIT  $offset , $pagesize";
    $count_sql = "SELECT count(*) FROM library where upper(author) like upper('%".$book_name."%')";
    break;
default:
    # code...
    break;
}

$result = mysql_query($count_sql);
if(!$result){
    exit('查询数据错误：'.mysql_error());
}
if( $back = mysql_fetch_array($result) ){
    $total_result = $back[0];
}

$result = mysql_query($query_sql);
if(!$result){
    exit('查询数据错误：'.mysql_error());
}
$results=array();
$i=0;
while($row=mysql_fetch_array($result)){
    $sub_arr = array();
    $sub_arr['marc_no'] = $row['marc_no'];
    $sub_arr['booktitle'] = $row['booktitle'];
    $sub_arr['author'] = $row['author'];
    $sub_arr['press'] = $row['press'];
    $sub_arr['publication_date'] = $row['publication_date'];
    $sub_arr['location'] = $row['location'];
    $sub_arr['call_number'] = $row['call_number'];
    $sub_arr['isbn'] = $row['isbn'];
    $results[$i]=$sub_arr;
    $i++;
}
echo JSON(array('results'=>$results,'type'=>$type,'current_page'=>$p,'total_result'=>$total_result,'total_page'=>ceil($total_result/$pagesize)));
?>
