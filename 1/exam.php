<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/exam.css" />
    <title>考试信息</title>
</head>
<body>
 <p>课程信息可以点击</p>
 <div class="contain" id="contain">
<?php
//1.json提取课程
//2.去重
//3.查询数据库
//4.组成str
//5.echo

//装载模板文件
require_once("./templates/wx_tpl.php");
require_once("./base-class.php");
require_once("./fun.php");

//新建sae数据库类


require_once("./libs/MYSQL_CONNECT.php");

// 确定当前页数 $p 参数
$id = $_GET['id']?$_GET['id']:'1';

if ($id=='1') {
    exit;
}

$json = select_kb($id);

$json_content=json_decode($json);
$arr = array();
for ($i=0; $i < 6; $i++) {
    foreach ($json_content->info[$i] as $key => $value) {
        exam_output($value,$arr);
    }
}
$i=0;


$sql= "SELECT class FROM id_students WHERE id = '$id'LIMIT 1";
$result = mysql_query($sql);
if(!$result){                          //不存在专业
    $ss = 0;
}
else{                                   //存在专业
    $ss = 1;
    $row = mysql_fetch_array($result);
    $a=str_split_unicode($row[0]);
}

foreach ($arr as $key => $value) {
    if ($value != '') {//防止数组空值
        if($ss==1){
            $query_sql = "SELECT * FROM  `exam_plan` WHERE course_name LIKE  '%".$value."%'and class LIKE '%".$a[2].$a[3]."%' ";
        }else{
            $query_sql = "SELECT * FROM  `exam_plan` WHERE course_name LIKE  '%".$value."%' ";
        }
        $result = mysql_query($query_sql);
        //div序号
        $course_name = array();
        while ( $back = mysql_fetch_array($result) ) {
            if ( is_array($back) ) {
                if ( $course_name[$back[3]] == '') {
                    $course_name[$back[3]].="&nbsp;&nbsp;&nbsp;&nbsp;".$back[0].$back[1].$back[2]."</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$back[4]."&nbsp;&nbsp;&nbsp;".$back[5]."</br>";
                } else {
                    $course_name[$back[3]].="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$back[4]."&nbsp;&nbsp;&nbsp;".$back[5]."</br>";
                }
            }
        }
        if (sizeof($course_name)>1) {
            # code...
            echo '<div class="name_title" onclick="javascript:change(\''.'name_'.$key.'\');"><a>'.$value."</a></br></div>";
            echo '<div id="'.'name_'.$key.'" style="display:none">';

            foreach ($course_name as $key => $value) {
                echo '<a href="javascript:change(\''.'small_'.$i.'\');">&nbsp;&nbsp;'.$key."</a></br>";
                echo '<div id="'.'small_'.$i.'" style="display:none">';
                echo $value;
                echo "</div>";
                $i++;
            }
            echo "</div>";
        } elseif (sizeof($course_name)==1) {
            echo '<div class="name_title" onclick="javascript:change(\''.'name_'.$key.'\');"><a>'.$value."</a></br></div>";
            echo '<div id="'.'name_'.$key.'" style="display:none">';
            foreach ($course_name as $key => $value) {
                echo $value;
            }
            echo "</div>";
        }
        unset($course_name);//释放arr
    }
}
unset($arr);//释放arr
?>
 </div>


</body>
</html>

<script>
function change(id){
    var obj=document.getElementById(id);
    if(obj.style.display=="none"){
        obj.style.display="block"
       }else{
           obj.style.display="none"
       }
   }
</script>
