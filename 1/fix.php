<?php
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");


$term = 16;
$last_term = 15;

//test
// fix_k('oRSMXuJJLuY-Tdvf304HKS2_Eczg');
// fix_s('oRSMXuJJLuY-Tdvf304HKS2_Eczg');
echo "<h1>Start!</h1><br/>";
echo "<h1>score:</h1><br/>";
//成绩部分
//成绩错误
$sql = "select id from id_cj where cj like '%DOCTYPE%' ";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
    echo "updating score where id = ".$value[0]."<br/>";
    fix_s($value[0]);
}
//成绩不存在
echo "<h1>score_not_exist:</h1><br/>";
$sql = "SELECT id FROM id_pw WHERE id NOT  IN ( SELECT id FROM id_cj)";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
    echo "updating cj where id = ".$value[0]."<br/>";
    cj_not_exist($value[0]);
}

//课表部分
//课表错误
echo "<h1>kebiao_DOCTYPE:\n</h1>";
$sql = "select id from id_kb where kb like '%DOCTYPE%'  ";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
    echo "updating kebiao where id = ".$value[0]."<br/>";
    fix_k($value[0]);
}


//课表不存在
echo "<h1>kebiao_not_exist:\n</h1>";
$sql = "SELECT id FROM id_pw WHERE id NOT IN ( SELECT id FROM id_kb)";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
    echo "updating kebiao where id = ".$value[0]."<br/>";
    kebiao_not_exist($value[0]);
}

echo "<h1>Over!</h1>";





function fix_s($id){
    global $last_term;
    $sql = "select id,pw from id_pw where id = '$id' ";
    $query = mysql_query($sql);
    $value = mysql_fetch_array($query);

    $id = $value[0];
    $pw = $value[1];

    $json_content = get_score_json($id,$pw,$last_term);

    if (strstr($json_content,"DOCTYPE")) {
        echo "failed<br/>";
    }
    else
    {
        $sql = "update id_cj set cj='$json_content' where id = '$id'";
        mysql_query($sql);
        echo "success<br/>";
    }
}

function cj_not_exist($id){
    global $last_term;
    $sql = "select id,pw from id_pw where id = '$id' ";
    $query = mysql_query($sql);
    $value = mysql_fetch_array($query);

    $id = $value[0];
    $pw = $value[1];
    $json_content = get_score_json($id,$pw,$last_term);

    if (strstr($json_content,"DOCTYPE")) {
        echo "failed<br/>";
    }
    else
    {
        insert_score($id,$json_content,$last_term);
        echo "success<br/>";
    }
}

function kebiao_not_exist($id){
    global $term;
    $sql = "select id,pw from id_pw where id = '$id' ";
    $query = mysql_query($sql);
    $value = mysql_fetch_array($query);

    $id = $value[0];
    $pw = $value[1];
    $json_content = get_kb_json($id,$pw);
    if (strstr($json_content,"DOCTYPE")) {
        echo "failed<br/>";
    }
    else
    {
        insert_kb($id,$json_content,$term);
        echo "success<br/>";
    }
}

function fix_k($id){
    global $term;
    $sql = "select id,pw from id_pw where id = '$id' ";
    $query = mysql_query($sql);
    $value = mysql_fetch_array($query);

    $id = $value[0];
    $pw = $value[1];
    $json_content = get_kb_json($id,$pw,$term);
    if (strstr($json_content,"DOCTYPE")) {
        echo "failed<br/>";
    }
    else
    {
        $sql = "update id_kb set kb='$json_content' where id = '$id'";
        mysql_query($sql);
        echo "success<br/>";
    }
}

?>
