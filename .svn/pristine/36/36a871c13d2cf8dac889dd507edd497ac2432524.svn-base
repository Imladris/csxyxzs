<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="css/lib.css" />
    <title>查询结果</title>
</head>
<body>
<span class="head_text">结果列表</span>

<?php
/**
 * 包含有文件
 */
require_once("./libs/MYSQL_CONNECT.php");
require_once("./config.php");
require_once("./fun.php");



// 确定当前页数 $p 参数
$p = $_GET['p']?$_GET['p']:1;
$book_name=$_GET['book_name']?$_GET['book_name']:'halfcrazy';

// 数据指针
$offset = ($p-1)*$pagesize;

$query_sql = "SELECT * FROM books where upper(book_name) like upper('%".$book_name."%') or upper(author_name) like upper('%".$book_name."%') LIMIT  $offset , $pagesize";
$result = mysql_query($query_sql);
// 如果出现错误并退出
if(!$result) exit('查询数据错误：'.mysql_error());


echo '<div class="list">';
// 循环输出
while($gb_array = mysql_fetch_array($result)){
    $content = nl2br($gb_array['content']);

    echo '<div class="card">';
    echo '<div class="book_name">《'.$gb_array['book_name'].'》</div>';
    echo '<div class="book_auth">'.'作者:'.$gb_array['author_name'].'</div>';
    echo '<div class="book_publ">'.'出版社:'.$gb_array['pressname'].'</div>';
    echo '<div class="book_time">'.'出版时间:'.$gb_array['presstime'].'</div>';

    $fangwei=null;
    switch ($gb_array['location'][0]) {
    case 'A':
        $fangwei='5楼东南区 ';
        break;
    case 'B':
        $fangwei='5楼东南区 ';
        break;
    case 'C':
        $fangwei='5楼东北区 ';
        break;
    case 'D':
        $fangwei='5楼西南区 ';
        break;
    case 'E':
        $fangwei='5楼东南区 ';
        break;
    case 'F':
        $fangwei='4楼东南区 ';
        break;
    case 'G':
        $fangwei='2楼西南区 ';
        break;
    case 'H':
        $fangwei='2楼西北区 ';
        break;
    case 'I':
        $fangwei='4楼西区 ';
        break;
    case 'J':
        $fangwei='5楼东北区 ';
        break;
    case 'K':
        $fangwei='4楼西南区 ';
        break;
    case 'N':
        $fangwei='2楼东北区 ';
        break;
    case 'O':
        $fangwei='2楼东北区 ';
        break;
    case 'P':
        $fangwei='2楼东北区 ';
        break;
    case 'Q':
        $fangwei='2楼东北区 ';
        break;
    case 'R':
        $fangwei='2楼东北区 ';
        break;
    case 'S':
        $fangwei='2楼东北区 ';
        break;
    case 'T':
        switch ($gb_array['location'][1]) {
        case 'P':
            $fangwei='3楼东南区 ';
            break;
        case 'S':
            $fangwei='2楼东北区 ';
            break;
        case 'U':
            $fangwei='3楼西南区 ';
            break;
        default:
            $fangwei='5楼西北区 ';
            break;
        }
        break;
    case 'U':
        $fangwei='5楼西北区 ';
        break;
    case 'V':
        $fangwei='5楼西北区 ';
        break;
    case 'X':
        $fangwei='5楼西北区 ';
        break;
    case 'Z':
        $fangwei='5楼西北区 ';
        break;
    }
    echo '<div class="book_loca">'.'藏书位置：'.$fangwei.$gb_array['location'].'</div>';
    echo '</div>';

}

echo '</div>';


$count_result = mysql_query("SELECT count(*) FROM books  where upper(book_name) like upper('%".$book_name."%')");
$count_array = mysql_fetch_array($count_result);
$pagenum=ceil($count_array['count(*)']/$pagesize);
if ($count_array['count(*)'] == 0) {
    echo "<br/>未查找到这本书<br/><br/>";
}
echo '<div class= "footer">';
echo '<div class="count">'.'共 ',$count_array['count(*)'],' 条记录'.'</div>';
if ($pagenum > 1) {
    if ($pagenum<=10) {
        # code...
        for($i=1;$i<=$pagenum;$i++) {
            if($i==$p) {
                echo '<div class="page_now"><span class="page_now">',$i,'</span></div>';
            } else {
                echo '<div class="page"><a href="?p='.$i.'&book_name='.$book_name.'" class="page">'.$i.'</a></div>';
            }
        }
    } else {
        for($i=1;$i<=$pagenum;$i++) {
            if($i==$p ) {
                echo '<div class="page_now"><span class="page_now">',$i,'</span></div>';
            } else if( $i==($p+2) || $i==($p+1) || $i==($p-2) ||
                $i==($p-1) || $i==1 || $i==$pagenum){

                if ( ($i==($p+2) && $i<$pagenum-1)||($i==($p-2) && $i>2) ) {
                    echo '<div class="page"><a href="?p='.$i.'&book_name='.str_password($book_name).'" class="page">'.'...'.'</a></div>';
                } else {
                    echo '<div class="page"><a href="?p='.$i.'&book_name='.str_password($book_name).'" class="page">'.$i.'</a></div>';
                }

            }
        }
    }
}
echo "</div>";
?>
</body>
</html>
