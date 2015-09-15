<?php
require("./model.php");

$route_list=array(
    "closehelp"=>"assistor_close_help",
    "exit"=>"assistor_exit",
    "test"=>"assistor_test",
    "bd"=>"assistor_student_bind",
    "jc"=>"assistor_student_remove",
    "tscx"=>"assistor_lib",
    "mxp"=>"assistor_gift",
    "ks"=>"assistor_exam",
    "news"=>"assistor_news",
    "notice"=>"assistor_notice",
    "cet"=>"assistor_cet",
    "kuaidi"=>"assistor_kd",
    "joke"=>"assistor_joke",
    "weather"=>"assistor_weather",
    "fj"=>"assistor_holiday",
    "cj"=>"assistor_grade",
    "kb"=>"assistor_kb",
    "st"=>"assistor_canteen",
    "qbst"=>"assistor_canteen_all",
    "teacherbd"=>"assistor_teacher_bind",
    "week"=>"assistor_week_kb",
    "general_course"=>"assistor_general_course",
    "citybox"=>"assistor_citybox",
    "vod"=>"assistor_vod",
    "statistic_st"=>"assistor_count_st",
    "order_food"=>"assistor_order_food",
    "wall"=>"assistor_wall",
    "random_food"=>"assistor_random_food",
    "game"=>"assistor_game",
);

function reduce_route($form_Content){
    if(strtolower($form_Content)=="help"){
        return array("help");
    }
    if($form_Content=="关闭帮助" || strtolower($form_Content)=="closehelp"){
        return array("closehelp");
    }
    if(strtolower($form_Content)=="exit" || strtolower($form_Content)=="q"){
        return array("exit");
    }
    if(strtolower($form_Content)=="test"){
        return array("test");
    }
    if( $form_Content=="绑定" || strtolower($form_Content)=="bd" ){
        return array("bd");
    }
    if( $form_Content=="解除" || $form_Content=="取消绑定" || $form_Content=="解除绑定" || strtolower($form_Content)=="jc" ){
        return array("jc");
    }
    if( $form_Content=="图书" || strtolower($form_Content)=="tscx" || $form_Content=="图书馆" || $form_Content=="书籍查询" || $form_Content=="图书查询"){
        return array("tscx");
    }
    if($form_Content=="我要明信片" ||$form_Content=="纪念品" || $form_Content=="明信片" || strtolower($form_Content)=="mxp"){
        return array("mxp");
    }
    if( $form_Content=="考试时间" || $form_Content=="考试" || strtolower($form_Content)=="ks" || strtolower($form_Content)=="exam" ){
        return array("ks");
    }
    if ($form_Content=="新闻" || $form_Content=="学院新闻" || strtolower($form_Content)=="news"){
        return array("news");
    }
    if ($form_Content=="通知" || $form_Content=="公告"|| $form_Content=="学院公告" || strtolower($form_Content)=="notice"){
        return array("notice");
    }
    if(strtolower($form_Content)=="cet" || form_Content=="四六级" || form_Content=="四六级成绩"|| form_Content=="四级" || form_Content=="六级"){
        return array("cet");
    }
    if( $form_Content=="快递" || $form_Content=="快递查询" || strtolower($form_Content)=="kd" || strtolower($form_Content)=="kuaidi" ){
        return array("kuaidi");
    }
    if( $form_Content=="哈哈" || $form_Content=="笑话" || strtolower($form_Content=="joke") ){
        return array("joke");
    }
    if($form_Content=="天气" || $form_Content=="大连天气" || $form_Content=="明天天气" || strtolower($form_Content)=="weather" ){
        return array("weather");
    }
    if($form_Content=="放假" || $form_Content=="放假时间" || strtolower($form_Content)=="fjxx" || strtolower($form_Content)=="fj" ){
        return array("fj");
    }
    if($form_Content=="历史成绩" || $form_Content=="上学期成绩" || strtolower($form_Content)=="cjcx" || $form_Content=="成绩"){
        return array("cj", -1);
    }
    if($form_Content=="本学期成绩" || $form_Content=="最新成绩"){
        return array("cj", 1);
    }
    if($form_Content=="课表" || $form_Content=="今天课表" || $form_Content=="今天" || strtolower($form_Content)=="jtkb" || strtolower($form_Content)=="kb"){
        return array("kb", 0);
    }
    if($form_Content=="明天" || $form_Content=="明天课表" || strtolower($form_Content)=="mt"){
        return array("kb", 1);
    }
    if($form_Content=="后天" || $form_Content=="后天课表" || strtolower($form_Content)=="ht"){
        return array("kb", 2);
    }
    if(strtolower($form_Content)=="st" || $form_Content=="食堂" || $form_Content=="订餐电话"){
        return array("st");
    }
    if(strtolower($form_Content)=="ls" || strtolower($form_Content)=="all" || $form_Content=="所有食堂" || $form_Content=="全部食堂" ){
        return array("qbst");
    }
    if( $form_Content=="教师绑定" || strtolower($form_Content)=="teacher" ){
        return array("teacherbd");
    }
    if( $form_Content=="周课表" || strtolower($form_Content)=="week" ){
        return array("week");
    }
    if( $form_Content=="通识课" || $form_Content=="选修课" || $form_Content=="选修" ){
        return array("general_course");
    }
    if( $form_Content=="城院盒子" || $form_Content=="盒子" || strtolower($form_Content)=="citybox" ){
        return array("citybox");
    }
    if( $form_Content=="vod更新" || $form_Content=="vod视频" || strtolower($form_Content)=="vod" ){
        return array("vod");
    }
    if( stristr($form_Content,"档口") ){
        return array("statistic_st");
    }
    if( $form_Content=="订餐" ){
        return array("order_food");
    }
    if( stristr($form_Content,"表白") || stristr($form_Content,"吐槽") || stristr($form_Content,"心愿") || stristr($form_Content,"墙")){
        return array("wall");
    }
    if( $form_Content=="今天吃什么" ){
        return array("random_food");
    }
    if( $form_Content=="game" ){
        return array("game");
    }
}
?>
