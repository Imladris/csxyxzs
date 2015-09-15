<?php

	function assistor_auto_reply(){
	    global $mc;
	    global $help_menu;
	    global $fromUsername;
	    global $form_Content;

	    //记录未知命令
	    //insert_time($form_Content);

	    $help_close = $mc->get($fromUsername."_closehelp");
	    if ($help_close != "true") {
	        $back = "你是不是想说"."\n".$help_menu."\n回复[关闭帮助]可暂时关闭自动回复~";
	        assistor_echo_text($back);
	    }
	}

	//绑定帐号提示输入密码
	function assistor_student_bind_step_0(){
	    global $mc;
	    global $fromUsername;
	    global $form_Content;

	    $mc->set($fromUsername."_do","bd_1",0,60);
	    $mc->set($fromUsername."_data",$form_Content,0,60);               //将学号存储于缓存中

	    $back = "请输入密码(注意:此步会耗费一些时间，可能不会返回消息或者返回[帮助]消息，直接使用即可)";
	    assistor_echo_text($back);
	}

	//绑定帐号确认是否成功
	function assistor_student_bind_step_1(){
	    global $kb_term;
	    global $cj_term;
	    global $mc;
	    global $fromUsername;
	    global $form_Content;

	    $openid = $fromUsername;
	    $id = $mc->get($openid."_data");       //学号从缓存中取出来
	    $pw = $form_Content;                   //用户输入的密码
	    //检验密码
	    if (verify($id,$pw) == false) {
	        $back = "账号或密码错误,请输入bd重新绑定";
	        $mc->delete($openid."_do");             //删除缓存中上次操作记录
	        $mc->delete($openid."_data");           //删除缓存中上次存储的数据
	    }
	    else
	    {
	        $mc->delete($openid."_do");
	        $mc->delete($openid."_data");
	        //数据库插入
	        $sql = "insert into openid_id values('$openid', '$id', now())";
	        if ( mysql_query($sql) )
	        {
	            $back = "绑定成功";

	            //获取成绩json
	            $score_json = get_score_json($id,$pw,$cj_term);
	            //insert chengji     
	            insert_score($id,$score_json,$cj_term);
	            //获取课表json
	            $kb_json = get_kb_json($id,$pw,$kb_term);
	            //insert kb
	            insert_kb($id,$kb_json,$kb_term);

	            $sql = "insert into id_pw values('$id', '$pw')";
	            if (mysql_query($sql) ) {//如果插入失败  则更新原有数据
	                $sql = "update id_pw set pw = '$pw' where id = '$id'";
	                mysql_query($sql);
	            }
	            //插入id_type
	            $sql = "insert into id_type values('$id', 's')";
	            mysql_query($sql);

	        } else {
	            $back = "绑定失败,请重新输入";
	        }
	    }
	    assistor_echo_text($back);
	}

?>