<?php
header("Content-type: text/html; charset=utf-8");
require_once './main.php';
require_once './conf.php';

$bool = urlSpider(MAIN_URL);
if ($bool) {
    die("爬取成功，并插入");
}else{
    die("爬取失败");
}


