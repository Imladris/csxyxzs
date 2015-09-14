<?php
require_once("./templates/schedule_tpl.php");
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");

// receive user_id
$id = $_GET['id']?$_GET['id']:'';
if ($id == '') {
    exit;
}

// receive week
$week = $_GET['week']?$_GET['week']:calc_week();
if($week>20){
    echo "week wrong";
    exit;
}

$user_id = get_id($id);
$json_content = select_kb($user_id);
$json = json_decode($json_content);
$back =null;

if ($json == '') {
    echo "wrong";
    exit;
}

$WeekDay = array("monday","tuesday","wednesday","thursday",
    "friday","saturday","sunday");

for($m = 0; $m < 6; $m++){
    for($i = 0; $i < 7; $i++){
        //获取第$m节课 周$i所有课程
        $str = $json->info[$m]->$WeekDay[$i];
        //分割课程
        $str=parse_ahead($str);
        $str_arry = my_str_split($str);

        $array_len=sizeof($str_arry);
        //判断每节课是否存在
        for($j=0;  $j<$array_len;  $j++)
        {
            $str_temp = str_handle($str_arry[$j],$week);
            if ( $str_temp != '') {
                $str_temp = fix_str($str_temp);

                $back .= $str_temp."|";
                break;
            }
            if ( $j == $array_len-1) {
                $back .= "|";
            }
        }
    }
}

$res=preg_split("/\|/", $back);

$page=sprintf($schedule_tpl,
    $id,
    $week,
    $res[0],$res[1],$res[2],$res[3],$res[4],$res[5],$res[6],
    $res[7],$res[8],$res[9],$res[10],$res[11],$res[12],$res[13],
    $res[14],$res[15],$res[16],$res[17],$res[18],$res[19],$res[20],
    $res[21],$res[22],$res[23],$res[24],$res[25],$res[26],$res[27],
    $res[28],$res[29],$res[30],$res[31],$res[32],$res[33],$res[34],
    $res[35],$res[36],$res[37],$res[38],$res[39],$res[40],$res[41]);
echo $page;

function fix_str($str){
    $str = str_replace("\n",",",$str);
    $str = str_replace(",2节","",$str);
    return $str;
}
?>













































