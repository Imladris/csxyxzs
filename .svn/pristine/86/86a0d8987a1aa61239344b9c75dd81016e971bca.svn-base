<?php
require_once('../libs/MYSQL_CONNECT.php'); 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $jokes = file_get_contents('php://input', 'r'); 
	$orderInfoArray = json_decode($jokes, true);
    $arrs = $orderInfoArray['jokes'];
    foreach ($arrs as $arr){
		$joke = trim($arr);
        $sql = "insert into joke(content) values('$joke')";
        mysql_query($sql);
    }
}
?>
