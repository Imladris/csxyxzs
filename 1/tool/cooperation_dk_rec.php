<?php
include_once("../libs/MYSQL_CONNECT.php");
header("Access-Control-Allow-Origin: *"); 
$smsapi = "api.smsbao.com"; //短信网关 
$charset = "utf8"; //文件编码 
$user = "artemis"; //短信平台帐号 
$pass = md5("artemis2012"); //短信平台密码 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $dk = $_POST['dk'];
    $cd = $_POST['cd'];
    $dz = $_POST['dz'];
    $tel = $_POST['tel'];
    $extra = $_POST['extra'];
    $sql = "select telephone from shitang where id=$dk";
    $rs = mysql_query($sql);
    $row=mysql_fetch_array($rs);
    $telephone = $row[0];
    $content = "$cd $dz $tel $extra";
    $content = urlencode($content);
    $sendurl = "http://$smsapi/sms?u=$user&p=$pass&m=$telephone&c=".$content;
    $ch = curl_init($sendurl) ;  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
    $output = curl_exec($ch) ;  
    $result = $output;
    if($result == 0){
        echo "订单已告诉商家，请等待。";
    }
    $sql = "insert into order_log(dk,cd,dz,tel,extra) values($dk,'$cd','$dz','$tel','$extra')";
    $rs = mysql_query($sql);
}
?>