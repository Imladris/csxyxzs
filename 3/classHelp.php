<?php
Class string
{

    /**
     * 过滤字符串中的<script ...>....</script>
     * @return string
     * @param string $str 需要过滤的字符
     */

    function un_script_code($str)
    {
        $s = array();
        $s["/<script[^>]*?>.*?<\/script>/si"] = "";
        return string::filt_string($str, $s, true);
    }

        /**
     * 过滤字符串中的特殊字符
     * @return string
     * @param string $str 需要过滤的字符
     * @param string $filtStr 需要过滤字符的数组（下标为需要过滤的字符，值为过滤后的字符）
     * @param boolen $regexp 是否进行正则表达试进行替换，默认false
     */

    function filt_string($str, $filtStr, $regexp = false)
    {
        if (!is_array($filtStr))
        {
            return $str;
        }
        $search     = array_keys($filtStr);
        $replace    = array_values($filtStr);

        if ($regexp)
        {
            return preg_replace($search, $replace, $str);
        }
        else
        {
            return str_replace($search, $replace, $str);
        }
    }
}


?>