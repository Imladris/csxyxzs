<?php
require './libs/MYSQL_CONNECT.php';
header("Access-Control-Allow-Origin: *");
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = $_POST['Name'];
    $class = $_POST['Class'];
    $tel = $_POST['Phone'];
    $exp = $_POST['Experienced'];
    $self= $_POST['Self'];

    $rs = mysql_query("select count(*) from fresher where name='$name' and tel='$tel'");
    $row=mysql_fetch_array($rs);
    if($row[0]==0){
        $sql = "insert into fresher(name,class,tel,exp,self) values('$name','$class','$tel','$exp','$self')";
        mysql_query($sql);
        echo "我们已经收到了~";
    }else{
        echo "请勿重复提交~";
    }
}
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "<html>";
    echo "<head>";
    echo '<meta charset="utf-8">';
    echo "</head>";
    echo "0.0";
    echo "</html>";
}
?>
