<?php
//本文件设置解析器和youtube代理
//如果本程序装在根目录，只需要修改$ym后面的域名即可，其他不必改动
$ym='http://acgfuck.herokuapp.com/'.$_SERVER['SERVER_NAME'].'/';//你的站点域名。不可为空，否则解析器无法工作
$parser='parser';//解析器目录,默认parser，如果本程序不是安装在根目录请加上上级路径，如abc/parser
//
$ytproxy='ytproxy';//youtube安装代理目录，默认是ytproxy不必改动。

?>
