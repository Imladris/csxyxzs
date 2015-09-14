<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>

<?php
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");
$term = 15;
//test
// fix_k('oRSMXuJJLuY-Tdvf304HKS2_Eczg');
echo "<h1>Start!</h1><br/>";

//update所有学生课表
echo "<h1>update all the :\n</h1>";

$sql = "select openid_id.id from openid_id where openid_id.id not in(select id_kb.id from id_kb) limit 20";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
    echo "updating kebiao where id = ".$value[0]."<br/>";
    fix_k($value[0]);
}

echo "<h1>Over!</h1>";

//update所有教师课表
$sql = "select id_type.id from id_type, id_kb where id_type.id=id_kb.id and type = 't' limit 20";
$query = mysql_query($sql);
while( $value = mysql_fetch_array($query) )
{
    echo "updating kebiao where id = ".$value[0]."<br/>";
    fix_t($value[0]);
}


function fix_t($id){
    global $term;
    $url = "http://1.csxyxzs1.sinaapp.com/teacher_schedule?id=%d";
    $target_url = sprintf($url,$id);
    $json_page = file_get_contents($target_url);
    $json = json_decode($json_page);
    if ($json->empty == "true")
    {
        return false;
    }
    else
    {
        //插入id_kb
        $sql = "replace into id_kb(id,kb,term,dat) values('$id','$json_page','$term',dat=1)";
        mysql_query($sql);
        return true;
    }
}



function fix_k($id){
    global $term;

    $sql = "select count(*) from id_kb where id= '$id' ";
    $query = mysql_query($sql);
    $value = mysql_fetch_array($query);
    $cnt = $value[0];

    if($cnt==0)
    {
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
            //replace into id_kb(id,kb,term,dat)values('{}','{}',{},1)
            $sql = "replace into id_kb(id,kb,term,dat) values('$id','$json_content','$term',dat=1)";
            mysql_query($sql);
            echo "success<br/>";
        }
    }
    else
    {
        echo "skip<br/>";
    }

}

?>

</body>
</html>
