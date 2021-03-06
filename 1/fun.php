<?php
/**
 * 教师绑定函数
 * global $term;
 * @param  [type] $openid [openid]
 * @param  [type] $id     [工号]
 * @return [type]         [绑定成功返回true]
 */
function teacher_bd($openid, $id){
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
        $sql = "insert into id_kb values('$id', '$json_page', '$term',0)";
        mysql_query($sql);
        //插入id_type
        $sql = "insert into id_type values('$id', 't')";
        mysql_query($sql);
        //插入openid_id
        $sql = "insert into openid_id values('$openid', '$id', now() )";
        mysql_query($sql);
        return true;
    }
}

/**
 * [明信片 是否记录检查]
 * @param  [type]  $openid [description]
 * @return boolean         [description]
 */
function is_order($openid){
    $sql = "SELECT openid FROM postcard WHERE openid = '$openid'";
    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) )
    {
        return $back[0];
    }
    else
    {
        return false;
    }
}

/**
 * [明信片 记录地址]
 * @param  [type] $openid  [description]
 * @param  [type] $address [description]
 * @return [type]          [description]
 */
function insert_address($openid,$address){
    $sql = "insert into postcard values('$openid','$address' )";
    mysql_query($sql);
}

/**
 * [判断是否绑定]
 * @param  [type]  $openid [description]
 * @return boolean          [description]
 */
function is_bd($openid){
    //select id
    $sql = "SELECT id FROM openid_id WHERE openid = '$openid' LIMIT 1";
    $result = mysql_query($sql);
    if ( $back = mysql_fetch_array($result) )
    {
        $id = $back[0];
        //获取账号type
        $sql = "SELECT type FROM id_type WHERE id = '$id' LIMIT 1";
        $result = mysql_query($sql);
        if( $back = mysql_fetch_array($result) )
        {
            return $back[0];
        }
        else
        {
            return false;
        }

    }
    else
    {
        return false;
    }

}

// /**
//  * 学生绑定
//  * @param  [type] $OpenId [description]
//  * @param  [type] $id     [description]
//  * @param  [type] $pw     [description]
//  * @return [type]         [description]
//  */
// function student_bd($OpenId,$id,$pw){
//     $type = 's';
//     $sql = "insert into user values('$OpenId','$id','$pw','$type',now())";
//     insert_time($sql);

//     if (mysql_query($sql)) {
//         $back = "绑定成功";
//     } else {
//         $back = "绑定失败,请重新输入";
//     }
//     return $back;
// }

/**
 * 检查学生账号密码
 * @param  [type] $username [id]
 * @param  [type] $password [pw]
 * @return [type]           [return true or false]
 */
function verify($username,$password){
    $base_url = "http://1.csxyxzs1.sinaapp.com/verify?name=%s&password=%s";
    $password = str_password($password);
    $target_url = sprintf($base_url,$username,$password);
    $json_page = file_get_contents($target_url);
    $json = json_decode($json_page);
    if ( $json->status == 'error' )
    {
        return false;
    }
    else if ($json->status == "success")
    {
        return true;
    }
}

/**
 * 处理字符串
 * @param  [type] $password [description]
 * @return [type]           [description]
 */
function str_password($password){
    $password = str_replace("%","%25",$password);
    $password = str_replace("+","%2B",$password);
    $password = str_replace("#","%23",$password);
    $password = str_replace("/","%2F",$password);
    $password = str_replace("&","%26",$password);
    $password = str_replace("~","%7e",$password);
    $password = str_replace("$","%24",$password);
    $password = str_replace("^","%5e",$password);
    return $password;
}

/**
 * [获取成绩]
 * @param  [type]  $username [description]
 * @param  [type]  $password [description]
 * @param  integer $term     [description]
 * @return [type]            [description]
 */
function get_score_json($username, $password, $term){
    $base_url = "http://1.csxyxzs1.sinaapp.com/grades?name=%s&password=%s&term=%s";
    $password = str_password($password);
    $target_url = sprintf($base_url,$username,$password,$term);
    $json_page = file_get_contents($target_url);
    return $json_page;
}

/**
 * 插入成绩
 * @param  [type]  $id   [description]
 * @param  [type]  $json [description]
 * @param  integer $term [description]
 * @return [type]        [description]
 */
function insert_score($id, $json, $term){
    $sql = "insert into id_cj values('$id','$json','$term')";
    if ( !mysql_query($sql)){//如果插入失败  则更新原有数据
        $sql = "update id_cj set cj = '$json',term='$term' where id = '$id'";
        mysql_query($sql);
    }
}

/**
 * 数据库获取score
 * @param  [type] $openid [description]
 * @return [type]          [description]
 */
function select_score($id){
    $sql= "SELECT cj FROM id_cj WHERE id = '$id' LIMIT 1";
    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) ){
        return $back[0];
    } else {
        return false;
    }
}
function get_id($openid){
    $sql= "SELECT id FROM openid_id WHERE openid = '$openid' LIMIT 1";
    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) ){
        return $back[0];
    } else {
        return false;
    }
}

/**
 * 格式化成绩
 * @param  [type] $json [description]
 * @return [type]       [description]
 */
function format_score($json){
    $json_content=json_decode($json);
    $contentStr =null;
    $status=$json_content->status;
    if($status=='error'){
        $contentStr="请输入正确的账号和密码";
    }  else {
        $n = 0;
        while( $json_content->info[$n] ){
            $contentStr.="科目:".$json_content->info[$n]->name."\n";
            $contentStr.="平时成绩:".$json_content->info[$n]->general_grades."\n";
            $contentStr.="期末成绩:".$json_content->info[$n]->final_grades."\n";
            $contentStr.="课程成绩:".$json_content->info[$n]->average_grades."\n";
            $contentStr.="\n";
            $n++;
        }
    }
    return $contentStr;
}

/**
 * 获取校网课表数据
 * @param  [type] $username [description]
 * @param  [type] $password [description]
 * @return [type]           [description]
 */
function get_kb_json($username,$password,$term){
    $base_url = "http://1.csxyxzs1.sinaapp.com/schedule?name=%s&password=%s&term=%s";
    $password = str_password($password);
    $target_url = sprintf($base_url,$username,$password,$term);
    $json_page = file_get_contents($target_url);
    return $json_page;
}

/**
 * 数据库插入课表
 * @param  [type] $id   [description]
 * @param  [type] $json [description]
 * @param  [type] $term [description]
 * @return [type]       [description]
 */
function insert_kb($id,$json,$term){
    $sql = "insert into id_kb values('$id','$json','$term',1)";
    if ( !mysql_query($sql) ) {//如果插入失败  则更新原有数据
        $sql = "update id_kb set kb = '$json',term='$term',dat='1' where id = '$id'";
        mysql_query($sql);
    }
}

/**
 * 从数据库中查课表
 * @param  [string] $openid [description]
 * @param  [type] $type      [student or teacher]
 * @return [type]            [description]
 */
function select_kb($id){
    $sql = "SELECT kb FROM id_kb WHERE id = '$id' LIMIT 1";

    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) ){

        return $back[0];
    } else {
        return false;
    }
}

/**
 * 单日课表合体!
 * @param  [type] $json_content [description]
 * @param  [type] $w            [description]
 * @param  [type] $week         [description]
 * @return [type]               [description]
 */
function detail_kb($json_content,$w,$week){
    $base = null;
    $base .= "第".$w."周  ".ucfirst($week)."\n";
    $sum = 0;
    $details = null;
    if ( generate_out( $json_content->info[0]->$week, $w ) != "" ){
        $sum += 1;
        $details .= "第1-2节:\n".generate_out( $json_content->info[0]->$week, $w )."\n\n";
    }
    if ( generate_out( $json_content->info[1]->$week, $w ) != "" ) {
        $sum += 1;
        $details .= "第3-4节:\n".generate_out( $json_content->info[1]->$week, $w )."\n\n";
    }
    if ( generate_out( $json_content->info[2]->$week, $w ) != "" ) {
        $sum += 1;
        $details .= "第5-6节:\n".generate_out( $json_content->info[2]->$week, $w )."\n\n";
    }
    if ( generate_out( $json_content->info[3]->$week, $w ) != "" ) {
        $sum += 1;
        $details .= "第7-8节:\n".generate_out( $json_content->info[3]->$week, $w )."\n\n";
    }
    if ( generate_out( $json_content->info[4]->$week, $w ) != "" ) {
        $sum += 1;
        $details .= "第9-10节:\n".generate_out( $json_content->info[4]->$week, $w )."\n\n";
    }
    if ( generate_out( $json_content->info[5]->$week, $w ) != "" ) {
        $sum += 1;
        $details .= "第11-12节:\n".generate_out( $json_content->info[5]->$week, $w )."\n\n";
    }
    if( $sum == 0 ){
        $base .="恭喜，全天没课！\n";
    }else{
        $base .="今天共".$sum."节课\n\n";
    }
    $base .= $details;
    return $base;
}

function calc_week($Date_1=0){
    if($Date_1==0){
        $Date_1=date("Y-m-d");
    }
    $Date_2="2015-9-6";
    $d1=strtotime($Date_1);
    $d2=strtotime($Date_2);
    $w=ceil(($d1-$d2)/3600/24/7);
    return $w;
}

/**
 * 格式化课表
 * @param  [type] $json [description]
 * @param  [date] $day [description]
 * @return [type]       [description]
 */
function format_kb($json,$day){
    $json_content=json_decode($json);
    $contentStr =null;

    if ($json_content == '') {
        return $contentStr;
    }
    //$week = strtolower(date("l"));
    $week = date("l",strtotime($day));
    $week = strtolower($week);
    //计算周数
    $w=calc_week($day);
    $contentStr .= detail_kb($json_content,$w,$week);
    return $contentStr;
}


function parse_ahead($str){
    $str=preg_replace("/([A-Za-z]) ([A-Za-z])/", "\\1_\\2", $str);
    return $str;
}

/**
 * 接受单节课表  分割出单个课程 交给str_handle()
 * @param  [type] $str [description]
 * @param  [type] $w   [description]
 * @return [type]      [description]
 */
function generate_out($str,$w){
    $out =null;
    $str=parse_ahead($str);
    $str_arry = my_str_split($str);
    $array_len=sizeof($str_arry);
    for($i=0;  $i<$array_len;  $i++)
    {
        $out .= str_handle($str_arry[$i],$w);
    }
    if ($out!="") {
        return $out;
    }
    else
    {
        return "";
    }
}

function my_str_split($str){
    $str = str_replace("节 ","节,",$str);
    $str_arr=preg_split("/,/", $str);
    return $str_arr;
}

function insert_time($str){
    $sql = "insert into log_data values('$str',now() )";
    mysql_query($sql);
}

/**
 * 删除openid用户所有内容
 * @param  [type] $openid [description]
 * @return [type]         [description]
 */
function del_user($openid){
    $sql = "DELETE FROM openid_id
        WHERE  openid='$openid'";
    mysql_query($sql);
}

/**
 * 单节课程处理
 * @param  [type] $str [description]
 * @param  [type] $w   [description]
 * @return [type]      [description]
 */
function str_handle($str,$w)
{
    $flag = false;


    $back_str = null;

    $patten = "/\s+/";
    $result = preg_split($patten,$str);
    if(count($result)==5)
    {
        $back_str .= $result[0]."\n";//课程名
        $back_str .= $result[1]."\n";//老师
        //$back_str .= $result[2];//周数
        // $back_str .= $result[3]."\n";//周类型
        $back_str .= $result[4];//时长
        $weekstr=substr($result[2], 0,strlen($result[2])-3);
        //$back_str .= $weekstr;
        $weekstr_arr= preg_split("/\./", $weekstr);
        //$back_str .= $weekstr_arr;
        //3.6-8.10-15.17
        foreach ($weekstr_arr as $value)
        {
            if(strchr($value,"-")==false)
            {
                if ($w == $value)
                {
                    $flag = true;
                }
            }
            else
            {
                $num=preg_split("/-/", $value);
                $start=$num[0];
                $end=$num[1];
                $num_arr=range($start,$end);
                if($result[3]=="单周")
                {
                    $array_len=sizeof($num_arr);
                    for($i=0;  $i<$array_len;  $i++)
                    {
                        if($num_arr[$i]%2==0)
                        {
                            unset($num_arr[$i]);
                        }
                    }
                }
                elseif ($result[3]=="双周") {
                    $array_len=sizeof($num_arr);
                    for($i=0;  $i<$array_len;  $i++)
                    {
                        if($num_arr[$i]%2==1)
                        {
                            unset($num_arr[$i]);
                        }
                    }
                }
                foreach ($num_arr as $item) {
                    if ($w == $item) {
                        $flag = true;
                        break;
                    }
                }
            }
        }
    }
    if(count($result)==6)
    {

        $back_str .= $result[0]."\n";//课程名
        $back_str .= $result[1]."\n";//老师
        $back_str .= $result[2]."\n";//教师
        //$back_str .= $result[3];//周数
        // $back_str .= $result[4]."\n";//周类型
        $back_str .= $result[5];//时长
        $weekstr=substr($result[3], 0,strlen($result[3])-3);
        //$back_str .= $weekstr;
        $weekstr_arr= preg_split("/\./", $weekstr);
        //$back_str .= $weekstr_arr;
        //3.6-8.10-15.17
        foreach ($weekstr_arr as $value) {
            if(strchr($value,"-")==false)
            {
                if ($w == $value)
                {
                    $flag = true;
                }
            }
            else
            {
                $num=preg_split("/-/", $value);
                $start=$num[0];
                $end=$num[1];
                $num_arr=range($start,$end);
                if($result[4]=="单周")
                {
                    $array_len=sizeof($num_arr);
                    for($i=0;  $i<$array_len;  $i++)
                    {
                        if($num_arr[$i]%2==0)
                        {
                            unset($num_arr[$i]);
                        }
                    }
                }
                elseif ($result[4]=="双周") {
                    $array_len=sizeof($num_arr);
                    for($i=0;  $i<$array_len;  $i++)
                    {
                        if($num_arr[$i]%2==1)
                        {
                            unset($num_arr[$i]);
                        }
                    }
                }
                foreach ($num_arr as $item) {

                    if ($w == $item) {
                        $flag = true;
                    }
                }
            }
        }
    }
    if ($flag)
    {

        return $back_str;
    }
    else
    {
        return "";
    }

}

/**
 * 正则 获取课程名
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function get_className($str)
{
    $patten = "/\s+/";
    $result = preg_split($patten,$str);
    return $result[0];
}

/**
 * [考试]
 * @param  [type] $str [description]
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
function exam_output($str,&$arr){
    $str=parse_ahead($str);
    $str_arry = my_str_split($str);
    $array_len=sizeof($str_arry);
    for($i=0;  $i<$array_len;  $i++)
    {
        $class_name = get_className($str_arry[$i]);
        if ( !in_array($class_name, $arr) && ($class_name != '') ) {
            $arr[] = $class_name;
        }
    }
}

/**
 * [substr_cut description]
 * @param  [type]  $str_cut [description]
 * @param  integer $length  [description]
 * @return [type]           [description]
 */
function substr_cut($str_cut,$length = 24)
{
    if (strlen($str_cut) > $length)
    {
        for($i=0; $i < $length; $i++)
            if (ord($str_cut[$i]) > 128)    $i++;
        $str_cut = substr($str_cut,0,$i)."..";
    }
    return $str_cut;
}

function random_food()
{
    $sql = "SELECT id,name
        FROM shitang
        WHERE id >= FLOOR( RAND( ) * (
            SELECT MAX( id )
            FROM shitang ) )
            LIMIT 1";
$result = mysql_query($sql);
if( $back = mysql_fetch_array($result) ){
    return $back[1];
} else {
    return false;
}
}
//逐个汉字遍历字符串
function str_split_unicode($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}
//录入id_students表
function insert_students($openid){
    $id = get_id($openid);
    $sql= "SELECT id,pw FROM id_pw WHERE id = '$id'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $pw = $row[1];

    $url = "http://1.csxyxzs1.sinaapp.com/info?name=".$id."&password=".$pw;
    $target_url = sprintf($url);
    $json_page = file_get_contents($target_url);
    $ary=explode("\"",$json_page);

    $sql = "insert into id_students values('$id','$ary[11]','$ary[15]','$ary[3]','$ary[7]','$ary[19]')";
    mysql_query($sql);
}
//id_students表是否存在该同学
function is_students($openid){
    $id = get_id($openid);
    $sql = "SELECT id FROM id_students WHERE id = '$id' LIMIT 1";
    $result = mysql_query($sql);
    if ( $back = mysql_fetch_array($result) )
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>
