<?php
//装载模板文件
include_once("wx_tpl.php");
include_once("base-class.php");
include_once("fun.php");

//新建sae数据库类


require("./MYSQL_CONNECT.php");

//新建Memcache类
$mc=memcache_init();

//获取微信发送数据
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

//操作菜单
$help_menu="输入'bd'绑定\n输入'jc'取消绑定\n查询成绩请输入'成绩'\n查询课表请输入'课表'\n查询明天课表请输入'明天'\n查询放假信息请输入'放假'\n图书馆信息查询请输入'图书'\n食堂信息查询请输入'食堂'\n回复'all'直接显示所有食堂！\n快递查询请输入'快递'\n回复'CET'查询四六级成绩\n";

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
          $form_MsgType = $postObj->MsgType;
    
        //欢迎消息
        if($form_MsgType=="event")
        {
            //获取事件类型
            $form_Event = $postObj->Event;
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
        }

        //用户文字回复进行绑定、查询等操作
        if($form_MsgType=="text")
        {
            //获取用户发送的文字内容并过滤
            $form_Content = trim($postObj->Content);
            $form_Content = string::un_script_code($form_Content);
            
            
           //如果发送内容不是空白则执行相应操作
            if(!empty($form_Content)||$form_Content==0)
            {

                //从memcache获取用户上一次动作
                $last_do=$mc->get($fromUsername."_do");
                //从memcache获取用户上一次数据
                $last_data=$mc->get($fromUsername."_data");

                //用户帮助提示
                if(strtolower($form_Content)=="help")
                {                        
                    //关注绑定欢迎词
                    $help_str=$help_menu;
                    //回复文字消息
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $help_str);
                    echo $resultStr;
                    exit;
                }
                
                //用户跳出操作
                if(strtolower($form_Content)=="exit" || strtolower($form_Content)=="q")
                {
                    //清空memcache动作
                    $mc->delete($fromUsername."_do");

                    //清空memcache数据
                    $mc->delete($fromUsername."_data");

                    //回复操作提示
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "你已经退出当前操作，请输入指令进行其他操作或输入“help”查看指令");
                    echo $resultStr;
                    exit;  
                }
                if(strtolower($form_Content)=="test")
                {
                    $back .= "fromUsername:".$fromUsername."\n";
                    $back .= "toUsername:".$toUsername."\n";
                    //消息类型
                    $back .= "form_MsgType:".$form_MsgType."\n";

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $back);
                    echo $resultStr;
                    exit;  
                }
//绑定
                if( $form_Content=="绑定" || strtolower($form_Content)=="bd" )
                {
                    if ( $back = is_bd($fromUsername) ) 
                    {
                        $back = "已绑定,回复jc解除绑定";
                    } 
                    else 
                    {
                        //校网检查
                        $fp = fsockopen("cityjw.dlut.edu.cn", 7001, $errno, $errstr, 2);
                        if (!$fp) 
                        {
                            $resp=sprintf($textTpl, $fromUsername, $toUsername, time(), "text", "Oops,校网又挂了,暂时不能绑定！囧rz");
                            echo $resp;
                            exit;
                        }
                        //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
                        $mc->set($fromUsername."_do", "bd_0", 0, 600);
                        $mc->set($fromUsername."_data", "0", 0, 600);
                        $back = "请输入学号";
                    }
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $back);
                    echo $resultStr;
                    exit;  
                }

                if($last_do=="bd_0")
                {
                    $mc->set($fromUsername."_do","bd_1",0,600);
                    $mc->set($fromUsername."_data",$form_Content,0,600);

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入密码(注意:此步会耗费一些时间，可能不会返回消息或者返回[帮助]消息，直接使用即可)");
                    echo $resultStr;
                exit;             
                }
                
                if($last_do=="bd_1")
                {             
                //get
                    $id = $mc->get($fromUsername."_data");
                    $pw = $form_Content;
                //检验密码
                    if (verify($id,$pw) == false) {
                        $back = "账号或密码错误,请输入bd重新绑定";
                        $msgType = "text";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $back);
                        echo $resultStr;
                        //清空memcache动作
                        $mc->delete($fromUsername."_do");
                        $mc->delete($fromUsername."_data");
                        exit;
                    }
                
                //del
                    $mc->delete($fromUsername."_do");
                    $mc->delete($fromUsername."_data");
                //sql
                    $sql = "insert into user values('$fromUsername','$id','$pw',now())";
                    if (mysql_query($sql)) 
                    {
                        $back = "绑定成功";
                        $msgType = "text";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $back);
                        echo $resultStr;

//获取成绩json
                        $score_json = get_score_json($id,$pw);
//insert chengji
                        insert_score($fromUsername,$score_json,14);
//获取课表json
                        $kb_json = get_kb_json($id,$pw);
//insert kb
                        insert_kb($fromUsername,$kb_json); 
                    } else {
                        $back = "绑定失败,请重新输入";
                        $msgType = "text";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $back);
                        echo $resultStr;
                    }
                exit;             
                }

//解除绑定
                if( $form_Content=="解除" || $form_Content=="取消绑定" || strtolower($form_Content)=="jc" )
                {
                    if ( $back = is_bd($fromUsername) ) 
                    {
                        del_all($fromUsername);
                        $back = "解绑成功";
                    } 
                    else 
                    {
                        $back = "还未绑定";
                    }
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $back);
                    echo $resultStr;
                    exit;  
                }
//图书馆
                if( $form_Content=="图书" || strtolower($form_Content)=="tscx" || strtolower($form_Content)=="图书馆" )
                {
                    //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
                    $mc->set($fromUsername."_do", "tscx_0", 0, 600);
                    $mc->set($fromUsername."_data", "0", 0, 600);

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入书名，或输入exit退出操作！");
                    echo $resultStr;
                    exit;  
                }

                if($last_do=="tscx_0")
                {
                    $book = $form_Content;
                    $form_Content = str_replace("+","%2B",$form_Content);
                    $form_Content = str_replace("#","%23",$form_Content);
                    $form_Content = str_replace("/","%2F",$form_Content);
                    $msgType='news';
                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, time(), $msgType,1, $book.' 查询结果','点开查看结果.若未查询到结果，请尝试更换关键字再次搜索.','http://csxyxzs-resources.stor.sinaapp.com/fenmian.jpg','http://1.csxyxzs.sinaapp.com/library.php?book_name='.$form_Content);
                    echo $resultStr;
                        
                    //清空memcache动作
                    $mc->delete($fromUsername."_do");
                    $mc->delete($fromUsername."_data");
                    exit;             
                }

//CET
                if(strtolower($form_Content)=="cet")
                {
                    $mc->set($fromUsername."_do", "cet_0", 0, 600);
                    $mc->set($fromUsername."_data", "0", 0, 600);
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入考试号");
                    echo $resultStr;
                    exit;  
                }

                if ($last_do == 'cet_0') {
                    $mc->set($fromUsername."_do", "cet_1", 0, 600);
                    $mc->set($fromUsername."_data", $form_Content, 0, 600);
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入姓名(前三个字即可)");
                    echo $resultStr;
                    exit; 
                }
                if ($last_do == 'cet_1') {
                    $base_url = "http://1.csxyxzs1.sinaapp.com/cet?num=%s&name=%s";
                    $num = $mc->get($fromUsername.'_data');
                    $nam = $form_Content;
                    $target_url = sprintf($base_url,$num,$nam);
                    $json_page = file_get_contents($target_url);
                    $json = json_decode($json_page);
                     if ( $json->status == 'error' ) 
                     {
                         $contentStr ="考号姓名有误或暂未找到记录。";
                     } 
                     else if ($json->status == "success") 
                     {
                         $contentStr ="等级:".$json->type."\n"."姓名:".$json->name."\n"."考号:".$json->num."\n"."学校:".$json->school."\n"."听力:".$json->listen."\n"."阅读:".$json->read."\n"."翻译与写作:".$json->write_translate."\n"."总分:".($json->listen+$json->read+$json->write_translate);
                     } 
                    
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;
                    //清空memcache动作
                    $mc->delete($fromUsername."_do");
                    $mc->delete($fromUsername."_data");
                    exit; 
                }

//快递
                if( $form_Content=="快递" || strtolower($form_Content)=="kd" || strtolower($form_Content)=="kuaidi" )
                {
                    //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
                    $mc->set($fromUsername."_do", "kdcx_0", 0, 600);
                    $mc->set($fromUsername."_data", "0", 0, 600);

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "请输入快递单号，或输入exit退出操作！");
                    echo $resultStr;
                    exit;  
                }

                if($last_do=="kdcx_0")
                {
                    $baseurl = 'http://1.csxyxzs1.sinaapp.com/kuaidi?postid=%s';
                    $postid = $form_Content;
                    $target_url = sprintf($baseurl,$postid);
                    $resp = file_get_contents($target_url);
                    if($resp!="")
                    {
                    $result_url=$resp;
                    $msgType='news';
                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, time(), $msgType,1, '单号'.$postid.'查询结果','点开查看结果.若未查询到结果，请确认单号无误后重新查询.','http://csxyxzs-resources.stor.sinaapp.com/kuaidifenmian.jpg',$result_url);
                    echo $resultStr;
                    }
                    else
                    {
                        $msgType='text';
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "单号无法识别，请确认后重新查询");
                        echo $resultStr;
                    }
                    //清空memcache动作
                    $mc->delete($fromUsername."_do");
                    $mc->delete($fromUsername."_data");
                    exit;             
                }

//放假
                if($form_Content=="放假" || strtolower($form_Content)=="fjxx" || strtolower($form_Content)=="fj" )
                {
                    $msgType = "text";
                    $contentStr ="2014年上半年法定节假日安排\n一、清明节：4月5日至7日放假，共3天，4月7日（星期一）停课。\n二、劳动节：5月1日至4日放假调休，共4天。4月27日（星期日）上班，并调上5月2日（星期五）的课程。\n三、端午节： 5月31日至6月2日放假，共3天，6月2日（星期一）停课。";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;
                    exit;
                }


//成绩                
                if($form_Content=="成绩" || strtolower($form_Content)=="cjcx")
                {
                //是否绑定
                    if (is_bd($fromUsername)) 
                    {
                    //读取Sae数据库
                        if ($json_content = select_score($fromUsername)) 
                        {
                            $contentStr = format_score($json_content);
                            if ($contentStr == "") {
                                $contentStr = "向学校查询成绩时出错，建议重新绑定下";
                            }
                        } 
                        else 
                        {
                            $contentStr = "正在更新数据，请稍后";
                        }
                    } 
                    else 
                    {
                        $contentStr = "请先绑定账号(回复bd)";
                    }
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;
                    exit;  
                }

//课表
                if($form_Content=="课表" || strtolower($form_Content)=="kb")
                {
                //是否绑定
                    if (is_bd($fromUsername)) 
                    {
                    //读取Sae数据库
                        if ($json_content = select_kb($fromUsername)) 
                        {
                            $contentStr = format_kb($json_content);
                            if ($contentStr == "") {
                                $contentStr = "向学校查询课表时出错，建议重新绑定下";
                            }
                        } 
                        else 
                        {
                            $contentStr = "正在更新数据，请稍后";
                        }
                    } 
                    else 
                    {
                            $contentStr = "请先绑定账号(回复bd)";
                    }
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;
                    exit;  
                }                
//明天课表
                if($form_Content=="明天" || strtolower($form_Content)=="mt")
                {
                //是否绑定
                    if (is_bd($fromUsername)) 
                    {
                    //读取Sae数据库
                        if ($json_content = select_kb($fromUsername)) 
                        {
                            $contentStr = nextday_kb($json_content);
                            if ($contentStr == "") {
                                $contentStr = "向学校查询课表时出错，建议重新绑定下";
                            }
                        } 
                        else 
                        {
                            $contentStr = "正在更新数据，请稍后";
                        }
                    } 
                    else 
                    {
                            $contentStr = "请先绑定账号(回复bd)";
                    }
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;
                    exit;  
                } 
//食堂
                if(strtolower($form_Content)=="st" || $form_Content=="食堂" || $form_Content=="订餐" || $form_Content=="订餐电话")
                {
                    //用memcache保存这步操作，格式为名称、值、有效时间(单位秒)；
                    $mc->set($fromUsername."_do", "st_0", 0, 600);
                    $mc->set($fromUsername."_data", "", 0, 600);
                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, "想要几号食堂的，输入exit退出操作！");
                    echo $resultStr;
                    exit;  
                }
                
                //第一步
                if($last_do=="st_0")
                {
                    switch ($form_Content) 
                    {
                        case '1':
                        case '一':
                        case '一食堂':
                            $st_locat= "一食堂";
                            break;
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
                        case 'all':
                        case 'All':
                            $st_locat = "all";
                            break;
                        default:
                            $wrong = "1";
                        break;
                    }

                    if ($wrong) 
                    {
                        $contentStr="没有这个食堂啊....\n请重新输入\n例:'1'或者'一'再或者'一食堂'";
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
                        
                        $back = mysql_query($sql);
                        $loop = 0;
                        while ( $db_value=mysql_fetch_array($back) ) 
                        {
                            //储存食堂编号
                            $arr[$loop] = $db_value[2];

                            $str_1 = $db_value[0];
                            // str_replace("\n","",$str_1);
                            $str_2 = $db_value[1];
                            // str_replace("/","\n",$str_2);
                            $contentStr.=$loop.".".$str_1.":\n".$str_2."\n";
                            $loop++;
                        }
                        $contentStr .= "\n\n回复对应编号查看菜单";
                        $mc->set($fromUsername."_do", "st_1", 0, 600);
                        $mc->set($fromUsername."_data", json_encode($arr), 0, 600);
                    }

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr );
                    echo $resultStr;
                    exit;  
                }
                if($last_do=="st_1")
                {
                    $arr = $mc->get($fromUsername."_data");
                    $arr = json_decode($arr);
                    $id = $arr[$form_Content];

                    if ( $id != '') 
                    {
                        $contentStr = "菜单:\n";
                        $sql = "SELECT name,price FROM `caidan` WHERE id='$id'";
                        $back = mysql_query($sql);
                        $loop = 0;
                        while ( $db_value=mysql_fetch_array($back) ) 
                        {
                            //储存食堂编号

                            $str_1 = $db_value[0];
                            // str_replace("\n","",$str_1);
                            $str_2 = $db_value[1];
                            // str_replace("/","\n",$str_2);
                            $contentStr.=$loop.".".$str_1." ".$str_2."\n";
                            $loop++;
                        }
                        //清空memcache动作
                        $mc->delete($fromUsername."_do");
                        $mc->delete($fromUsername."_data");
                    }
                    else
                    {
                        $contentStr = "编号错了,重新输入(输入exit退出操作)";
                    }

                    $msgType='text';
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;

                    exit;             
                }


//所有食堂
                if(strtolower($form_Content)=="ls" || strtolower($form_Content)=="all" || $form_Content=="所有食堂" || $form_Content=="全部食堂" )
                {
                    $sql = "select name,telephone from shitang";
                    $str = mysql_query($sql);
                    while ( $str_value=mysql_fetch_array($str) ) 
                    {
                        $str_name = $str_value[0];
                        // str_replace("\n","",$str_name);
                        $str_tel = $str_value[1];
                        // str_replace("/","\n",$str_tel);
                        $contentStr.=$str_name.":\n".$str_tel."\n";
                    }

                    $msgType = "text";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr);
                    echo $resultStr;
                    exit;  
                }
                //用户自动回复
                $msgType = "text";
                $contentStr = "不知道你在说啥=.=，需要帮助回help啊~"."\n".$help_menu;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $msgType, $contentStr );
                echo $resultStr;
                exit;  
            }
        }
    } 
    else 
    {
        echo "";
        exit;
    }
?>