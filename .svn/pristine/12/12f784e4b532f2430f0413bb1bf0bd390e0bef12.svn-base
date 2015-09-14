<?php
function is_bd($open_id){
    $sql = "select OpenId from user where OpenId = '$open_id'";
    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) ){
        return $back[0];
    } else {
        return false;
    }
}

function bd_bd($OpenId,$id,$pw){
    $sql = "insert into user values('$OpenId','$id','$pw',now())";
    if (mysql_query($sql)) {
        $back = "绑定成功";
    } else {
        $back = "绑定失败,请重新输入";
    }
    return $back;
}
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

function get_score_json($username,$password){
    $base_url = "http://1.csxyxzs1.sinaapp.com/grades?name=%s&password=%s";
    $password = str_password($password);
    $target_url = sprintf($base_url,$username,$password);
    $json_page = file_get_contents($target_url);
    return $json_page;
}    

function insert_score($openid,$json,$term = 13){
    $sql = "insert into chengji values('$openid','$json','$term',now())";
    mysql_query($sql);
}

function select_score($open_id){
    $sql= "SELECT score FROM chengji WHERE openid = '$open_id' LIMIT 1";
    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) ){
        return $back[0];
    } else {
        return false;
    }
}
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


function get_kb_json($username,$password){
    $base_url = "http://1.csxyxzs1.sinaapp.com/schedule?name=%s&password=%s";
    $password = str_password($password);
    $target_url = sprintf($base_url,$username,$password);
    $json_page = file_get_contents($target_url);
    return $json_page;
} 

function insert_kb($openid,$json){
    $sql = "insert into kebiao values('$openid','$json')";
    mysql_query($sql);
}

function select_kb($open_id){
    $sql= "SELECT ke_biao FROM kebiao WHERE openid = '$open_id' LIMIT 1";
    $result = mysql_query($sql);
    if( $back = mysql_fetch_array($result) ){
        return $back[0];
    } else {
        return false;
    }
}
      

function format_kb($json){
    $json_content=json_decode($json);
    $contentStr =null;
    
    if ($json_content == '') {
        return $contentStr;
    }
    $week = strtolower(date("l"));
    //计算周数
    $w = calc_week();
    $contentStr .=   "第".$w."周  ".$week."\n";
    $contentStr .=   "第1-2节:\n".generate_out( $json_content->info[0]->$week, $w )."\n\n";
    $contentStr .=   "第3-4节:\n".generate_out( $json_content->info[1]->$week, $w )."\n\n";
    $contentStr .=   "第5-6节:\n".generate_out( $json_content->info[2]->$week, $w )."\n\n";
    $contentStr .=   "第7-8节:\n".generate_out( $json_content->info[3]->$week, $w )."\n\n";
    $contentStr .=  "第9-10节:\n".generate_out( $json_content->info[4]->$week, $w )."\n\n";
    $contentStr .= "第11-12节:\n".generate_out( $json_content->info[5]->$week, $w )."\n\n";



    return $contentStr;
}        

function nextday_kb($json){
    $json_content=json_decode($json);
    $contentStr =null;
    
    if ($json_content == '') {
        return $contentStr;
    }
    $Date_1=date('Y-m-d',strtotime('+1 day'));
    $Date_2="2014-3-2";
    $d1=strtotime($Date_1);
    $d2=strtotime($Date_2);
    $w=ceil(($d1-$d2)/3600/24/7);
    $week = strtolower(date("l",strtotime('+1 day')));
    //计算周数
    $contentStr .= "第".$w."周  ".$week."\n";
    $contentStr .= "第1-2节:\n".generate_out( $json_content->info[0]->$week, $w )."\n\n";
    $contentStr .= "第3-4节:\n".generate_out( $json_content->info[1]->$week, $w )."\n\n";
    $contentStr .= "第5-6节:\n".generate_out( $json_content->info[2]->$week, $w )."\n\n";
    $contentStr .= "第7-8节:\n".generate_out( $json_content->info[3]->$week, $w )."\n\n";
    $contentStr .= "第9-10节:\n".generate_out( $json_content->info[4]->$week, $w )."\n\n";
    $contentStr .= "第11-12节:\n".generate_out( $json_content->info[5]->$week, $w )."\n\n";

    return $contentStr;
} 

function parse_ahead($str){
    $str=preg_replace("/([A-Za-z]) ([A-Za-z])/", "\\1_\\2", $str);
    return $str;
}

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
        return "哈哈，这节没课~";
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
function del_all($openid){
    $sql = "DELETE kebiao,user,chengji FROM kebiao,user,chengji WHERE  user.OpenId =  '$openid' and kebiao.openid=user.OpenId AND chengji.openid=user.OpenId  ";
    mysql_query($sql);
}

function calc_week(){
    $Date_1=date("Y-m-d");
    $Date_2="2014-3-2";
    $d1=strtotime($Date_1);
    $d2=strtotime($Date_2);
    $w=ceil(($d1-$d2)/3600/24/7);
    return $w;
}
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
?>