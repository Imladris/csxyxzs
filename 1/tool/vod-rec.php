<?php
require_once('../libs/MYSQL_CONNECT.php'); 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $movies = $_POST['movies'];
	$orderInfoArray = json_decode($movies, true);
    foreach ($orderInfoArray as $arr){
    	$name = $arr['name'];
    	$id = $arr['id'];
    	$episode = $arr['episode'];
    	$update_time = $arr['update_time'];
  		//判断视频是否已在数据库中出现，出现则无脑update，未出现则insert
  		$sql = "select count(*) from vod_update where id=$id";  
		$rs = mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_array($rs);
        $cnt = $row[0];
        if($cnt==0){
	    	$sql = "insert into vod_update(id,name,episode,update_time) values($id,'$name',$episode,'$update_time') ";
        }else{
        	$sql = "update vod_update set episode='$episode',update_time='$update_time' where id=$id";
        }
    	mysql_query($sql);
    }
}
?>
