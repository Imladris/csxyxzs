<?php
    require_once("../libs/MYSQL_CONNECT.php");
    $p = $_GET['p']?$_GET['p']:1;
    if($p<1){
        $p = 1;
    }
    $rs = mysql_query("select count(*) from vod_update");
    $row = mysql_fetch_array($rs);
    $total = $row[0];
    $perNumber = 10;
    $startCount = ($p-1)*$perNumber;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no, minimal-ui">
    <title>VOD更新</title>
    <link href="css/lala.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">VOD视频更新</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="http://1.csxyxzs.sinaapp.com/page/about.htm">关于我们</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="panel panel-default">
        <?php
            $sql = "SELECT ts FROM vod_update ORDER BY ts DESC LIMIT 1";
            $rs = mysql_query($sql);
            $row = mysql_fetch_array($rs);
            $tm = $row[0];
            $d = strtotime($tm);
            $dt = date("Y-m-d", $d);
            echo"<div class='panel-heading'>最近更新 截止至$dt</div>";
        ?>
                <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>视频名</th>
                        <th>最新集数</th>
                        <th>更新时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        global $startCount;
                        global $perNumber;
                        $result=mysql_query("select name,episode,update_time from vod_update order by update_time desc limit $startCount,$perNumber");
                        $i = 1;
                        while ($row=mysql_fetch_array($result)) {
                            $name = $row[0];
                            $episode = $row[1];
                            $update_time = $row[2];
                            echo "<tr>
                                    <td>$i</td>
                                    <td>$name</td>
                                    <td>$episode</td>
                                    <td>$update_time</td>
                                </tr>";
                            $i = $i + 1;
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <!--下面是分页部分-->
        <ul class="pagination">
            <?php
                global $total;
                global $perNumber;
                global $p;
                $max_p = ceil($total/$perNumber);
                if($p==1){
                        echo"<li class='disabled'><a href='#'>&laquo;前一页</a>
                        </li>";
                    }else{
                        $pp = $p - 1;
                        echo"<li><a href='?p=$pp'>&laquo;前一页</a>
                        </li>";
                    }

                for($it=1;$it<=5;$it=$it+1){
                    if($p==$it){
                        echo"<li class='active'><a href='?p=$it'>$it</a>
                        </li>";
                    }else{
                        if($it>$max_p){
                            echo"<li class='disabled'><a href='#'>$it</a>
                            </li>";
                        }else{
                            echo"<li><a href='?p=$it'>$it</a>
                            </li>";
                        }
                    }
                }

                if($p>=$max_p){
                    echo"<li class='disabled'><a href='#'>&raquo;后一页</a>
                    </li>";
                }else{
                    $np = $p + 1;
                    echo"<li><a href='?p=$np'>&raquo;后一页</a>
                    </li>";
                }
            ?>
        </ul>
    </div>
    <div class="container">
        <div class="row">
            <div id="footer" class="span12">
                <p>♥ Do have faith in what you're doing.</p>
                <p>Producted by <a href="http://1.csxyxzs.sinaapp.com/page/about.htm">城市学院小助手</a> </p>
            </div>  
        </div>
    </div>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>

</html>

