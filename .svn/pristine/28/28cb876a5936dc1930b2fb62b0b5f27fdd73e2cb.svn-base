<?php
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



?>