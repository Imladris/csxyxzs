<?php
require_once('../libs/MYSQL_CONNECT.php');
$smsapi = "api.smsbao.com"; //短信网关 
$charset = "utf8"; //文件编码 
$user = "artemis"; //短信平台帐号 
$pass = md5("artemis2012"); //短信平台密码 

echo '<html><head><meta charset="utf-8"></head><body>';
$sql = "select name,tel,isSent from fresher where isSent=0";
$rs = mysql_query($sql) or die(mysql_error());
$cnt = mysql_num_rows($rs);
echo "共 $cnt 条短信需要发<br/><br/>";
while ( $row=mysql_fetch_array($rs) ) 
{
	$name = $row[0];
	$tel = $row[1];
	$isSent = $row[2];
	if($isSent == 1){
		continue;
	}
	$content = $name."同学您好，uit将于10月11日上午10点和下午1点半分别举行2场纳新考试，2场任意参加一场且只允许参加一场，请准时参加。也请代别人填报的同学通知一下本人。收到请回复收到。注：这个号码不用存";
	$content = urlencode($content);
	$sendurl = "http://$smsapi/sms?u=$user&p=$pass&m=$tel&c=".$content;
	$ch = curl_init($sendurl) ;  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
	$output = curl_exec($ch) ;  
	$result = $output;
	if($result == 0){
		$update_stsm = "update fresher set isSent=1 where name = '$name' and tel = '$tel' ";
		mysql_query($update_stsm);
		$cnt = $cnt - 1;
		$fmtstr = sprintf("%10s %12s 已发送----------剩余 %4s 条待发。<br/>",$name,$tel,$cnt);
		echo $fmtstr;
	}else if($result == -1){
		echo $name." ".$tel." 发送失败<br/>";
	}else{
		echo $result."<br/>";
	}
}
echo '</body></html>';

?>
