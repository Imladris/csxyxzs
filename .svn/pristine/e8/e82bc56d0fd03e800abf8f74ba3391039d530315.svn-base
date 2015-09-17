<?php
//装载模板文件
require_once("./templates/wx_tpl.php");
require_once("./controller.php");
require_once("./base-class.php");
//新建sae数据库类
require_once("./libs/MYSQL_CONNECT.php");

/**
 * 全局内容
 * term 学期
 */
$term = 15;
$last_term = 14;

//新建Memcache类
$mc=memcache_init();

//获取微信发送数据
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//操作菜单
$help_menu="
回复[bd]绑定学号
回复[jc]解除绑定
回复[时刻表]查询每日时刻表
回复[成绩]查询成绩
回复[课表]查询本日课表
回复[明天]查询明天课表
回复[周课表]查询周课表
回复[放假]查询放假信息
回复[图书]查询图书馆信息
回复[食堂]查询食堂信息
回复[all]直接显示所有食堂
复[快递]查询快递
回复[微信墙]进入微信墙
回复[天气]查询大连实时天气
回复[笑话]查看笑话
回复[CET]查询四六级成绩
回复[考试]查询考试时间安排
回复[新闻]查看校内资讯
回复[通知]查看学校最新通知
回复[选修]查看本学期选修课
回复[vod]查看vod最近更新
回复[game]玩小游戏
回复[盒子]查看城院盒子介绍
回复[教师绑定]进行教师绑定
";
//返回回复数据
if (!empty($postStr))
{
    //解析数据
    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    //发送消息方ID
    $fromUsername = $postObj->FromUserName;
    //接收消息方ID
    $toUsername = $postObj->ToUserName;
    //消息类型
    $form_MsgType = strtolower($postObj->MsgType);

    $isClick=false;
    $isVoice=false;

    //欢迎消息
    if($form_MsgType=="event")
    {
        //获取事件类型
        $form_Event = strtolower($postObj->Event);
        //订阅事件
        if($form_Event=="subscribe")
        {
            $welcome_str="感谢您关注城院小助手！[愉快]\n\n".$help_menu;
            //回复欢迎文字消息
            $msgType = "text";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $welcome_str);
            echo $resultStr;
            exit;
        }
        if($form_Event=="click")
        {
            $isClick=true;
        }
    }
    else if($form_MsgType=="voice")
    {
        $isVoice=true;
    }

    //用户文字回复进行绑定、查询等操作
    if($form_MsgType=="text" || $isClick==true || $isVoice==true)
    {
        //获取用户点击菜单的key
        if($isClick==true)
        {
            $form_Content = trim($postObj->EventKey);
            $form_Content = string::un_script_code($form_Content);
        }
        //获取用户语音输入内容
        else if($isVoice==true)
        {
            $form_Content = trim($postObj->Recognition);
            $form_Content = string::un_script_code($form_Content);
        }
        else
        {
            //获取用户发送的文字内容并过滤
            $form_Content = trim($postObj->Content);
            $form_Content = string::un_script_code($form_Content);
        }
        //如果发送内容不是空白则执行相应操作
        if(!empty($form_Content)||$form_Content==0)
        {

            //从memcache获取用户上一次动作
            $last_do=$mc->get($fromUsername."_do");
            //从memcache获取用户上一次数据
            $last_data=$mc->get($fromUsername."_data");

            //这放route函数
            $operate=reduce_route($form_Content);
            $funcname = $operate[0];
            $params = $operate[1];

            if(array_key_exists($funcname,$route_list)){
                if(count($operate) == 1)
                    $route_list[$funcname]();
                else{
                    $route_list[$funcname]($params);
                }
                exit;
            }
            //绑定

            if($last_do=="bd_0")
            {
                assistor_student_bind_step_0();
                exit;
            }

            if($last_do=="bd_1")
            {
                assistor_student_bind_step_1();
                exit;
            }

            //图书馆

            if($last_do=="tscx_0")
            {
                assistor_lib_book();
                exit;
            }

            //纪念品

            if($last_do=="gift_0")
            {
                assistor_gift_addres();
                exit;
            }

            //考试时间
            //news
            //notice    201407151110
            //CET 

            if ($last_do == 'cet_0') {
                assistor_cet_step_0();
                exit;
            }
            if ($last_do == 'cet_1') {
                assistor_cet_step_1();
                exit;
            }

            //快递

            if($last_do=="kdcx_0")
            {
                assistor_kd_step_0();
                exit;
            }

            //放假
            //历史成绩
            //最新成绩
            //课表
            //明天课表
            //食堂
            //第一步
            if($last_do=="st_0")
            {
                assistor_canteen_step_0();
                exit;
            }
            if($last_do=="st_1")
            {
                assistor_canteen_step_1();
                exit;
            }


            //所有食堂
            //teacher_course
            if($last_do=="t_bd_0")
            {
                assistor_teacher_bind_step_0();
                exit;
            }
            
            //teacher 课表
            //解除绑定
            //用户自动回复
            assistor_auto_reply();
            exit;
            //查询时刻表
           
        }
    }
    else if($form_MsgType=="image")
    {
        //此处处理图片，实际应用需考虑mc上一步操作
        assistor_echo_text("照片不错哦~");
        exit;
    }
}
else
{
    echo "";
    exit;
}
?>
