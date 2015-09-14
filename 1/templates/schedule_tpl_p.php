<?php
$schedule_tpl=
'
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
<title>课表</title>
<link href="css/main.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
</head>
<body onload="load_weeks(\'%s\')">
<div class="container">
<div class="navbar">
<div class="navbar-inner">
<div class="container">
<a class="brand" href="#">课表</a>
<ul class="nav pull-right">
<li><a class="dropdown-toggle" data-toggle="dropdown" href="#">周目 <span class="caret"></span></a>
<ul class="dropdown-menu" id="weekslist">
</ul>
</li>
<li><a href="http://1.csxyxzs.sinaapp.com/page.htm">关于</a></li>
</ul>                    
</div>
</div>
</div>
<div class="table-responsive">
<table class="table table-hover">
<caption>
<h1 class="page-header">当前第%s周</h1></caption>
<thead>
<tr>
<th></th>
<th>Mon</th>
<th>Tue</th>
<th>Wed</th>
<th>Thu</th>
<th>Fri</th>
<th>Sat</th>
<th>Sun</th>
</tr>
</thead>
<tbody>
<tr>
<td>1-2</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
<tr>
<td>3-4</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
<tr>
<td>5-6</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
<tr>
<td>7-8</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
<tr>
<td>9-10</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
<tr>
<td>11-12</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
<td>%s</td>
</tr>
</tbody>
</table>
</div>
<div>
<blockquote class="pull-right"><p>在寻求真理的长河中，唯有学习，不断地学习，勤奋地学习，有创造性地学习，才能越重山跨峻岭。</p><small><cite>华罗庚</cite></small></blockquote>
</div>
</div>
<div class="container">
<div class="row">
<div id="footer" class="span12">
<p>♥ Do have faith in what you\'re doing.</p>
<p>Producted by <a href="http://1.csxyxzs.sinaapp.com/page/about.htm">城市学院小助手</a> </p>
</div>  
</div>
</div>
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/schedule.js"></script>
</body>
</html>
';
?>

