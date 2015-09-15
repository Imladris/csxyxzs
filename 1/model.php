<?php
require_once("./templates/wx_tpl.php");
require_once("./fun.php");
require_once("./libs/MYSQL_CONNECT.php");


function assistor_echo_text($str){
    global $fromUsername;
    global $toUsername;
    global $textTpl;

    $msgType = "text";
    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $str);
    echo $resultStr;
}
function assistor_echo_news($title, $description, $picUrl, $url){
    global $newsTpl;
    global $fromUsername;
    global $toUsername;

    $msgType='news';
    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, time(), $msgType,1, $title,$description,$picUrl,$url);
    echo $resultStr;
}
function assistor_help(){
    global $help_menu;
    //关注绑定欢迎词
    $help_str=$help_menu;
    //回复文字消息
    assistor_echo_text($help_str );

}

function assistor_close_help(){
    global $mc;
    global $fromUsername;

    $mc->set($fromUsername."_closehelp", "true", 0, 600);
    assistor_echo_text("自动回复会关闭10min");
}
function assistor_exit(){
    global $mc;
    global $fromUsername;

    //清空memcache动作
    $mc->delete($fromUsername."_do");
    //清空memcache数据
    $mc->delete($fromUsername."_data");

    $back = "你已经退出当前操作，请输入指令进行其他操作或输入[help]查看指令";
    assistor_echo_text($back);
}

function assistor_test(){
    global $fromUsername;
    global $toUsername;

    $back .= "fromUsername:".$fromUsername."\n";
    $back .= "toUsername:".$toUsername."\n";

    assistor_echo_text($back);
}

function assistor_joke(){
    $sql = "SELECT content FROM joke ORDER BY rand() LIMIT 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $joke = $row[0];
    assistor_echo_text($joke);
}

function assistor_weather(){
    /*
    //用mc来做参数传递
    global $mc;
    global $fromUsername;
    $cityname = $mc->get($fromUsername."_city");
    $mc->delete($fromUsername."_city");
     */
    $url = "http://v.juhe.cn/weather/index?cityname=%E5%A4%A7%E8%BF%9E&key=9a8dc37abaf5e2e97ccdb17f27765a2a";
    $json_page = file_get_contents($url);
    $json = json_decode($json_page);
    $output="大连天气:\n";
    $output.="实时气温:".$json->result->sk->temp."\n";
    $output.="实时风力:".$json->result->sk->wind_strength."\n";
    $output.="今日天气:".$json->result->today->weather."\n";
    $output.="今日温度:".$json->result->today->temperature."\n";
    $output.="今日风力:".$json->result->today->wind."\n";
    $output.="穿衣建议:".$json->result->today->dressing_advice."\n";
    $day="day_".date("Ymd");
    $output.="次日气温:".$json->result->future->$day->temperature."\n";
    $output.="次日天气:".$json->result->future->$day->weather."\n";
    assistor_echo_text($output);
}

function assistor_student_bind(){
    global $mc;
    global $fromUsername;

    if ( $back = is_bd($fromUsername) )
    {
        $back = "已绑定,回复[jc]解除绑定";
    }
    else
    {
        //校网检查
        $fp = fsockopen("cityjw.dlut.edu.cn", 7001, $errno, $errstr, 2);
        if (!$fp)
        {

            $back = "Oops,校网又挂了,暂时不能绑定！囧rz";
        }
        else
        {
            //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
            $mc->set($fromUsername."_do", "bd_0", 0, 600);
            $mc->set($fromUsername."_data", "0", 0, 600);
            $back = "请输入学号";
        }
    }
    assistor_echo_text($back);
}

function assistor_student_bind_step_0(){
    global $mc;
    global $fromUsername;
    global $form_Content;

    $mc->set($fromUsername."_do","bd_1",0,600);
    $mc->set($fromUsername."_data",$form_Content,0,600);

    $back = "请输入密码(注意:此步会耗费一些时间，可能不会返回消息或者返回[帮助]消息，直接使用即可)";
    assistor_echo_text($back);
}

function assistor_student_bind_step_1(){
    global $term;
    global $mc;
    global $fromUsername;
    global $form_Content;

    $openid = $fromUsername;
    $id = $mc->get($openid."_data");
    $pw = $form_Content;
    //检验密码
    if (verify($id,$pw) == false) {
        $back = "账号或密码错误,请输入bd重新绑定";

        $mc->delete($openid."_do");
        $mc->delete($openid."_data");
    }
    else
    {
        $mc->delete($openid."_do");
        $mc->delete($openid."_data");
        //sql
        $sql = "insert into openid_id values('$openid', '$id', now())";
        if ( mysql_query($sql) )
        {
            $back = "绑定成功";

            //获取成绩json
            $score_json = get_score_json($id,$pw,14);
            //insert chengji     $term = 14 2014上半年  历史成绩
            insert_score($id,$score_json,14);
            //获取课表json
            $kb_json = get_kb_json($id,$pw,$term);
            //insert kb
            insert_kb($id,$kb_json,$term);


            $sql = "insert into id_pw values('$id', '$pw')";
            if (mysql_query($sql) ) {//如果插入失败  则更新原有数据
                $sql = "update id_pw set pw = '$pw' where id = '$id'";
                mysql_query($sql);
            }
            //插入id_type
            $sql = "insert into id_type values('$id', 's')";
            mysql_query($sql);
            //插入openid_id
            $sql = "insert into pw values('$id', '$pw' )";
            mysql_query($sql);

        } else {
            $back = "绑定失败,请重新输入";
        }
    }
    assistor_echo_text($back);
}

function assistor_student_remove(){
    global $fromUsername;

    $openid = $fromUsername;
    $back = is_bd($openid);
    if ( $back)
    {
        del_user($openid);
        $back = "解绑成功";
    }
    else
    {
        $back = "还未绑定";
    }
    assistor_echo_text($back);
}

function assistor_lib(){
    global $mc;
    global $fromUsername;

    $openid = $fromUsername;
    //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
    $mc->set($openid."_do", "tscx_0", 0, 600);
    $mc->set($openid."_data", "0", 0, 600);

    $back = "请输入书名，或输入exit退出操作！";
    assistor_echo_text($back);
}

function assistor_lib_book(){
    global $form_Content;

    $book = $form_Content;
    $base_book = $book;
    $book = str_replace("+","%2B",$book);
    $book = str_replace("#","%23",$book);
    $book = str_replace("/","%2F",$book);


    $title = $base_book.' 查询结果';
    $description = '点击查看查询结果,回复[exit]退出查询';
    $picUrl = 'http://csxyxzs-resources.stor.sinaapp.com/fenmian.jpg';
    $url = 'http://1.csxyxzs.sinaapp.com/library.php?book_name='.$book;

    assistor_echo_news($title, $description, $picUrl, $url);
}

function assistor_gift(){
    global $fromUsername;
    global $mc;
    $have_act = false;
    $openid = $fromUsername;
    if ($have_act) {
        if (is_order($openid))
        {
            $back = "已经记下你的地址咯哦";
        }
        else
        {
            $mc->set($openid."_do", "gift_0", 0, 600);
            $mc->set($openid."_data", "0", 0, 600);
            $back = "好的,告诉我你的地址吧~(要邮编，地址，姓名一个都不能少哦";
        }
    } else {
        $back = "现在还没有活动..";
    }

    assistor_echo_text($back);
}

function assistor_gift_addres(){
    global $mc;
    global $fromUsername;
    global $form_Content;

    $openid = $fromUsername;
    $addres = $form_Content;

    insert_address($openid,$addres);

    $back = "地址已经记下了~";
    assistor_echo_text($back);

    $mc->delete($fromUsername."_do");
    $mc->delete($fromUsername."_data");
}

function assistor_exam(){
    global $fromUsername;
    $open = true;
    $openid = $fromUsername;
    //是否绑定
    if (!is_bd($openid))
    {
        $back = "请先绑定账号(回复bd)";
        assistor_echo_text($back);
        exit;
    }

    if(!$open){
        $back = "考试已经结束啦";
        assistor_echo_text($back);
        exit;
    }

    if(!is_students($openid))                     //id_students表是否存在该同学
    {
        insert_students($openid);
    }

    //读取Sae数据库
    $id = get_id($openid);
    if ( $json_content = select_kb($id) )
    {
        $day=date("l");
        $back = format_kb($json_content);
        if ($back == "") {
            $back = "向学校查询课表时出错，建议重新绑定下";
        } else {
            $title = ' 考试日期';
            $description = '可以把这条消息加入收藏哦！直接打开就能看考试时间';
            $picUrl = 'https://csxyxzs.sinaapp.com/img/exam.png';
            $url = 'http://1.csxyxzs.sinaapp.com/exam.php?id='.$id;
            assistor_echo_news($title, $description, $picUrl, $url);
            //assistor_echo_text($openid);
            exit;
        }
    }
    else
    {
        $back = "正在更新数据，请稍后";
    }

    assistor_echo_text($back);
}

function assistor_news(){
    global $fromUsername;
    global $toUsername;
    global $start;
    global $item;
    global $end;

    $firstPic = "https://csxyxzs.sinaapp.com/img/school.png";
    $otherPic = "https://csxyxzs.sinaapp.com/img/white.png";
    $contents_url = "https://www.kimonolabs.com/api/4wb4h75u?apikey=600efbca1fa20767b1ab92a9283f2b1d";

    //拼装头
    $msgType = "news";
    $back = sprintf($start, $fromUsername,
        $toUsername, time(), $msgType,9);
    $school_news = file_get_contents($contents_url);
    $school_news = json_decode($school_news);

    //拼装内容
    for($n =0; $n < 9; $n++){
        $collection = $school_news->results->collection1[$n+1];

        $title = $collection->description->text;
        $url   = $collection->description->href;

        if ($n == 0) {
            $back .= sprintf($item, $title, "kaka", $firstPic, $url);
        } else {
            $back .= sprintf($item, $title, "kaka", $otherPic, $url);
        }
    }
    //拼装尾
    $back .= $end;
    echo $back;
}

function assistor_notice(){
    global $fromUsername;
    global $toUsername;
    global $start;
    global $item;
    global $end;

    $firstPic = "https://csxyxzs.sinaapp.com/img/school.png";
    $otherPic = "https://csxyxzs.sinaapp.com/img/white.png";
    $contents_url = "https://www.kimonolabs.com/api/7tyqahu2?apikey=600efbca1fa20767b1ab92a9283f2b1d";

    //拼装头
    $msgType = "news";
    $back = sprintf($start, $fromUsername, $toUsername, time(), $msgType,9);
    $school_notice = file_get_contents($contents_url);
    $school_notice = json_decode($school_notice);

    //拼装内容
    for($n =0; $n < 9; $n++){
        $collection = $school_notice->results->collection1[$n+1];

        $url   = $collection->title->href;
        $date  = $collection->date;
        $title = $collection->title->text;
        $department =  $collection->department.":\n";

        if ($n== 0) {
            $back .= sprintf($item, $department.$title, "kaka", $firstPic, $url);
        } else {
            $back .= sprintf($item, $department.$title.$date, "kaka", $otherPic, $url);
        }
    }
    //拼装尾
    $back .= $end;
    echo $back;
}

function assistor_cet(){
    global $fromUsername;
    global $mc;

    $mc->set($fromUsername."_do", "cet_0", 0, 600);
    $mc->set($fromUsername."_data", "0", 0, 600);

    $back = "请输入考试号";

    assistor_echo_text($back);
}

function assistor_cet_step_0(){
    global $mc;
    global $form_Content;
    global $fromUsername;

    $mc->set($fromUsername."_do", "cet_1", 0, 600);
    $mc->set($fromUsername."_data", $form_Content, 0, 600);

    $back = "请输入姓名(前三个字即可)";

    assistor_echo_text($back);
}

function assistor_cet_step_1(){
    global $mc;
    global $fromUsername;
    global $form_Content;

    $openid = $fromUsername;

    $base_url = "http://1.csxyxzs1.sinaapp.com/cet?num=%s&name=%s";
    $num = $mc->get($openid.'_data');
    $nam = $form_Content;

    $target_url = sprintf($base_url,$num,$nam);
    $json_page = file_get_contents($target_url);
    $json = json_decode($json_page);

    if ( $json->status == 'error' )
    {
        $back ="考号姓名有误或暂未找到记录。";
    }
    else if ($json->status == "success")
    {
        $back ="等级:".$json->type."\n"."姓名:".$json->name."\n"."考号:".$json->num."\n"."学校:".$json->school."\n"."听力:".$json->listen."\n"."阅读:".$json->read."\n"."翻译与写作:".$json->write_translate."\n"."总分:".($json->listen+$json->read+$json->write_translate);
    }

    assistor_echo_text($back);
    //清空memcache动作
    $mc->delete($openid."_do");
    $mc->delete($openid."_data");
}

function assistor_kd(){
    global $mc;
    global $fromUsername;

    //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
    $mc->set($fromUsername."_do", "kdcx_0", 0, 600);
    $mc->set($fromUsername."_data", "0", 0, 600);
    $back = "请输入快递单号(不用输入快递公司)，或输入exit退出操作！";
    assistor_echo_text($back);
}


function assistor_kd_step_0(){
    global $mc;
    global $form_Content;

    $baseurl = 'http://1.csxyxzs1.sinaapp.com/kuaidi?postid=%s';
    $postid = $form_Content;

    $target_url = sprintf($baseurl,$postid);
    $resp = file_get_contents($target_url);

    if($resp!="")
    {
        $url=$resp;
        $title = '单号'.$postid.'查询结果';
        $picUrl = 'http://csxyxzs-resources.stor.sinaapp.com/kuaidifenmian.jpg';
        $description = '点开查看结果.若未查询到结果.请确认单号无误后重新查询.';
        assistor_echo_news($title, $description, $picUrl, $url);
    }
    else
    {
        $back = "单号无法识别，请确认后重新查询";
        assistor_echo_text($back);
    }
    //清空memcache动作
    $mc->delete($fromUsername."_do");
    $mc->delete($fromUsername."_data");
}

function assistor_holiday(){

    $week = 20-calc_week();;
    $back ="2015年下半年法定节假日安排 中秋节9.26-9.27，国庆节10.1-10.7，10.8-10.10上课时间，10.11正常休息。";
    assistor_echo_text($back);
}

function assistor_grade($flag){
    global $fromUsername;
    global $term;
    //temp fix
    // $msgType = "text";
    // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "Oops,校网又挂了,暂时不能查询！囧rz");
    // echo $resultStr;
    // exit;
    if($flag == -1)
        $_term = $last_term;
    elseif ($term == 1) {
        $_term = $term;
    }
    $openid = $fromUsername;
    //是否绑定
    if (is_bd($openid))
    {
        //下面这段获取用户名和密码有问题，改好应该就能用了。
        $id = get_id($openid);
        $sql= "SELECT id,pw FROM id_pw WHERE id = '$id'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $id = $row[0];
        $pw = $row[1];


        $score_json = get_score_json($id,$pw,$_term);
        $back = format_score($score_json);

        if ($back == '') {
            $back = "暂无本学期成绩,回复[cjcx]可以查看上学期成绩!";
        }
    }
    else
    {
        $back = "请先绑定账号(回复bd)";
    }
    assistor_echo_text($back);
}

function assistor_kb($offset){
    global $term;
    global $fromUsername;

    global $mc;
    global $form_Content;

    $openid = $fromUsername;

    $id = get_id($openid);
    $pw = $form_Content;
    //是否绑定
    if (is_bd($openid))
    {

        //读取Sae数据库
        if ($json_content = select_kb($id))
        {
            $day=date("Y-m-d",strtotime('+'.$offset.' day'));
            $back = format_kb($json_content,$day);
            if ($back == "") {
                $back = "向学校查询课表时出错，建议重新绑定下";
            }
        }
        else
        {
            $back = "课表数据已更新，请再试一次";
            //获取课表json
            $sql= "SELECT id,pw FROM id_pw WHERE id = '$id'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $pw = $row[1];
            $kb_json = get_kb_json($id,$pw,$term);
            insert_kb($id,$kb_json,$term);
        }
        //$back = "课表功能暂时无法使用，我们正在修复，给您带来的不便，敬请谅解！";
    }
    else
    {
        $back = "请先绑定账号(回复bd)";
    }
    assistor_echo_text($back);
}

function assistor_canteen(){
    global $mc;
    global $fromUsername;

    //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
    $mc->set($fromUsername."_do", "st_0", 0, 600);
    $mc->set($fromUsername."_data", "", 0, 600);

    assistor_echo_text("想要几号食堂的，输入exit退出操作！") ;
}

function assistor_canteen_step_0(){
    global $mc;
    global $fromUsername;
    global $form_Content;

    $openid = $fromUsername;

    switch ($form_Content)
    {
    case '2':
    case '二':
    case '二食堂':
        $st_locat= "二食堂";
        break;
    case '3':
    case '三':
    case '三食堂':
        $st_locat= "三食堂";
        break;
    default:
        $wrong = "1";
        break;
    }

    if ($wrong)
    {
        $back="没有这个食堂\n请重新输入\n例:'2'或者'二'再或者'二食堂'";
    }
    else
    {
        if($st_locat == 'all')
        {
            $sql = "select name,telephone,id from shitang";
        }
        else
        {
            $sql = "select name,telephone,id from shitang where location ='$st_locat'";
        }

        $sql_back = mysql_query($sql);
        $loop = 0;
        $back = "";
        while ( $db_value=mysql_fetch_array($sql_back) )
        {
            //储存食堂编号
            $arr[$loop] = $db_value[2];

            $str_1 = $db_value[0];
            // str_replace("\n","",$str_1);
            $str_2 = $db_value[1];
            // str_replace("/","\n",$str_2);
            $back.=$loop.".".$str_1."\n".$str_2."\n";
            $loop++;
        }
        $back .= "\n\n回复对应编号查看菜单";
        $mc->set($openid."_do", "st_1", 0, 600);
        $mc->set($openid."_data", json_encode($arr), 0, 600);
    }

    assistor_echo_text($back);
}

function assistor_canteen_step_1(){
    global $mc;
    global $fromUsername;
    global $form_Content;

    $arr = $mc->get($fromUsername."_data");
    $arr = json_decode($arr);
    $id = $arr[$form_Content];

    if ( $id != '')
    {
        //$back = "菜单:".$id;
        $back = "菜单:\n";
        $sql = "SELECT name,price FROM `caidan` WHERE id='$id'";
        $sql_back = mysql_query($sql);
        //$loop = 0;
        while ( $db_value=mysql_fetch_array($sql_back) )
        {
            //储存食堂编号

            $str_1 = $db_value[0];
            // str_replace("\n","",$str_1);
            $str_2 = $db_value[1];
            // str_replace("/","\n",$str_2);
            $back.=$str_1." ￥".$str_2."\n";
            //$loop++;
            //$str_1 = $db_value[0];
            // str_replace("\n","",$str_1);
            //$str_2 = $db_value[1];
            // str_replace("/","\n",$str_2);
            //$back.=$loop.".".$db_value[0]." ".$db_value[1]."\n";
            
            //$back.=$loop.".".$db_value[0]."\n";
            //$loop++;
        }
        //清空memcache动作
        $mc->delete($fromUsername."_do");
        $mc->delete($fromUsername."_data");
    }
    else
    {
        $back = "编号错了,重新输入(输入exit退出操作)";
    }

    assistor_echo_text($back);
}

function assistor_canteen_all(){

    $sql = "select name,telephone from shitang";
    $str = mysql_query($sql);
    while ( $str_value=mysql_fetch_array($str) )
    {
        $str_name = $str_value[0];
        // str_replace("\n","",$str_name);
        $str_tel = $str_value[1];
        // str_replace("/","\n",$str_tel);
        $back.="档口：".$str_name."\n"."电话：".$str_tel."\n";
    }

    assistor_echo_text($back);
}

function assistor_teacher_bind(){
    global $mc;
    global $fromUsername;

    if ( is_bd($fromUsername) )
    {
        $back = "已绑定,回复[jc]解除绑定";
    }
    else
    {
        // temp fix
        // $msgType = "text";
        // $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "Oops,校网又挂了,暂时不能绑定！囧rz");
        // echo $resultStr;
        // exit;

        //校网检查
        $fp = fsockopen("cityjw.dlut.edu.cn", 7001, $errno, $errstr, 2);
        if (!$fp)
        {
            $back = "Oops,校网又挂了,暂时不能绑定！囧rz";
        }
        else
        {
            //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
            $mc->set($fromUsername."_do", "t_bd_0", 0, 600);
            $mc->set($fromUsername."_data", "0", 0, 600);
            $back = "请输入工号";
        }
    }
    assistor_echo_text($back);
    exit;
}

function assistor_teacher_bind_step_0(){
    global $mc;
    global $fromUsername;
    global $form_Content;

    if(teacher_bd($fromUsername, $form_Content) )
    {
        $back = "绑定成功~回复[jc]解除绑定";
    }
    else
    {
        $back = "绑定失败,未查询到数据";
    }

    assistor_echo_text($back);

    $mc->delete($fromUsername."_do");
    $mc->delete($fromUsername."_data");
}


function assistor_auto_reply(){
    global $mc;
    global $help_menu;
    global $fromUsername;
    global $form_Content;

    //记录未知命令
    insert_time($form_Content);

    $help_close = $mc->get($fromUsername."_closehelp");
    if ($help_close != "true") {
        $back = "你是不是想说"."\n".$help_menu."\n回复[关闭帮助]可暂时关闭自动回复~";
        assistor_echo_text($back);
    }
}

/**
 * week
 * @return [type] [description]
 */
function assistor_week_kb(){
    global $fromUsername;
    $openid = $fromUsername;
    //是否绑定
    if (!is_bd($openid))
    {
        $back = "请先绑定账号(回复bd)";
        assistor_echo_text($back);
        exit;
    }

    $title = '周课表';
    $description = '可以把这条消息加入收藏哦！直接打开就能看';
    $picUrl = 'https://csxyxzs.sinaapp.com/img/schedule.jpg';
    $url = 'http://1.csxyxzs.sinaapp.com/schedule.php?id='.$openid;
    assistor_echo_news($title, $description, $picUrl, $url);
    exit;

}

function assistor_general_course(){
    global $fromUsername;
    $openid = $fromUsername;

    if (!is_bd($openid))
    {
        $back = "请先绑定账号(回复bd)";
        assistor_echo_text($back);
        exit;
    }
    //获取密码
    $id = get_id($openid);
    $sql = "select pw from id_pw where id = '$id'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $pw = $row[0];

    $base_url = "http://1.csxyxzs1.sinaapp.com/general_course?name=%s&password=%s";
    $pw = str_password($pw);
    $target_url = sprintf($base_url,$id,$pw);
    //get json
    $json = file_get_contents($target_url);
    $json = json_decode($json);
    //
    $count = sizeof($json->general_course);
    for($i=0; $i<$count; $i++){
        $back .= $json->general_course[$i]."\n";
    }

    assistor_echo_text($back);
    exit;
}

function assistor_citybox(){
    $title = '城院盒子';
    $description = '城院盒子简介即下载地址,打开后请点右上角在浏览器中打开，否则可能无法下载。';
    $picUrl = 'https://csxyxzs.sinaapp.com/img/citybox.png';
    $url = 'http://fir.im/citybox';
    assistor_echo_news($title, $description, $picUrl, $url);
    exit;
}

function assistor_vod(){
    $title = 'vod更新';
    $description = 'vod近期更新,点开查看';
    $picUrl = 'https://csxyxzs.sinaapp.com/img/vod.png';
    $url = "http://1.csxyxzs.sinaapp.com/page/vod.php";
    assistor_echo_news($title, $description, $picUrl, $url);
    exit;
}

function assistor_count_st()
{
    global $fromUsername;
    global $form_Content;
    $sql = "insert into count_st(id,msg) values('$fromUsername', '$form_Content')";
    mysql_query($sql);
    assistor_echo_text("已统计。");
    exit;
}

function assistor_order_food()
{
    $title = '食堂订餐';
    $description = '食堂订餐模块';
    $picUrl = 'https://csxyxzs.sinaapp.com/img/food.jpg';
    $url = "http://csxyxzs.sinaapp.com/tool/dc.html";
    assistor_echo_news($title, $description, $picUrl, $url);
    exit;
}

function assistor_wall()
{
    $title = '小助手微信墙';
    $description = '表白，吐槽，心愿~';
    $picUrl = 'https://csxyxzs.sinaapp.com/img/wall.jpg';
    $url = "http://csxywxq.sinaapp.com/w/";
    assistor_echo_news($title, $description, $picUrl, $url);
    exit;
}

function assistor_random_food()
{
    $food = random_food();
    assistor_echo_text("试试 ".$food." 吧.");
    exit;
}

function assistor_game()
{
    $title = '朋友圈小游戏';
    $description = '快来挑战吧~';
    $picUrl = 'https://csxyxzs.sinaapp.com/logo.png';
    $url = "http://csxyxzs.sinaapp.com/game/dinosaur_game/dinosaur.htm";
    assistor_echo_news($title, $description, $picUrl, $url);
    exit;
}


?>
