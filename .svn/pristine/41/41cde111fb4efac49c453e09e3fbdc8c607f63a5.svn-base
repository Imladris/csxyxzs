<?php
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");
$sql = "select * from id_pw";
$rs = mysql_query($sql);
while($row=mysql_fetch_row($rs)){
    print_r($row);
    $id = $row[0];
    $pw = $row[1];
    $sql1 = "SELECT id FROM id_students WHERE id = '$id' LIMIT 1";
    $result1 = mysql_query($sql1);
    if ( $back = mysql_fetch_array($result1) )
    {
        continue;
    }
    else
    {
        $url = "http://1.csxyxzs1.sinaapp.com/info?name=".$id."&password=".$pw;
        $target_url = sprintf($url);
        $json_page = file_get_contents($target_url);
        $ary=explode("\"",$json_page);
        //if($ary[11]==""){break;}

        $sql = "insert into id_students values('$id','$ary[11]','$ary[15]','$ary[3]','$ary[7]','$ary[19]')";
        mysql_query($sql);
        break;
    }
}


?>
