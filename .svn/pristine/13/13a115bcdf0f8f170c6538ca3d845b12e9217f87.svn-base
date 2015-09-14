<?php
require_once("./libs/MYSQL_CONNECT.php");

function cache_news($collection,$type)
{
    foreach ($collection as $key) {
        $text =  $key->title->text;
        $href = $key->title->href;
        if ($type == "xw") {
            $description = $key->content;
            $sql = "insert into xyxw(text,description,href) values('$text','$description','$href')";
            mysql_query($sql);
        }elseif ($type == "gg") {
            $sql = "insert into xygg(text,href) values('$text','$href')";
            mysql_query($sql);
        }
    }
}
$xw_url = "https://www.kimonolabs.com/api/bg3nrkz0?apikey=600efbca1fa20767b1ab92a9283f2b1d";
$gg_url = "https://www.kimonolabs.com/api/arrsbbjc?apikey=600efbca1fa20767b1ab92a9283f2b1d";

$json_page = file_get_contents($xw_url);
$json = json_decode($json_page);
$collection1 = $json->results->collection1;

$json_page = file_get_contents($gg_url);
$json = json_decode($json_page);
$collection2 = $json->results->collection1;

cache_news($collection1,"xw");
cache_news($collection2,"gg");
?>
