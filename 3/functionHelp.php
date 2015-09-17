<?php

include ("./mysqlHandle.php");
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
 * [判断是否绑定]
 * @param  [type]  $openid [description]
 * @return boolean          [description]
 */
function is_bd($openid){
    //select id
    $sql = "SELECT id FROM _openid_id WHERE openid = '$openid' LIMIT 1";
    $result = mysql_query($sql);

    if ( $back = mysql_fetch_array($result) )
    {
        $id = $back[0];
        //获取账号type(暂时不需要)
        //$sql = "SELECT type FROM id_type WHERE id = '$id' LIMIT 1";
        //$result = mysql_query($sql);
        //if( $back = mysql_fetch_array($result) )
        //{
        //    return $back[0];
        //}
        //else
        //{
        //    return false;
        //}
        return $id;                //如果绑定则返回学号
    }
    else
    {
        return false;
    }

}

/**
 * 删除openid用户所有内容
 * @param  [type] $openid [description]
 * @return [type]         [description]
 */
function del_user($openid){
    $sql = "DELETE FROM _openid_id WHERE openid='$openid'";
    mysql_query($sql);
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
 * 数据库插入课表
 * @param  [type] $id   [description]
 * @param  [type] $json [description]
 * @param  [type] $term [description]
 * @return [type]       [description]
 */
function insert_kb($id,$json,$kb_term){
    $sql = "insert into _id_kb values('$id','$json','$kb_term',1)";
    if ( !mysql_query($sql) ) {//如果插入失败  则更新原有数据
        $sql = "update _id_kb set kb = '$json',term='$kb_term',dat='1' where id = '$id'";
        mysql_query($sql);
    }
}





?>
