<?php
require './libs/MYSQL_CONNECT.php';
header("Access-Control-Allow-Origin: *");
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $content = $_POST['content'];
    $user = $_POST['user'];
    $id = $_POST['id'];
    $sql = "insert into feedback(user,content,id) values('$user','$content','$id')";
    mysql_query($sql);
    echo "我们已经收到了~";
}
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo "<html>";
    echo "<head>";
    echo '<meta charset="utf-8">';
    echo '<script src="js/func.js"></script>';
    echo "</head>";
    $sql = "select * from feedback order by post_date desc";
    $rs = mysql_query($sql) or die(mysql_error());
    while ( $row=mysql_fetch_array($rs) )
    {
        $user = $row[0];
        $content = $row[1];
        $id = $row[2];
        $post_date = $row[3];
        echo $post_date."\t".$user."\t".$id."<br>";
        echo $content."<br>"."<br>"."<br>"."<br>";
    }
    echo "</html>";
}
?>
