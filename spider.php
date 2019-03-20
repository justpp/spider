<?php

use Symfony\Component\DomCrawler\Crawler;

require_once 'vendor/autoload.php';
require_once './curl.php';
require_once './conf.php';

function getUrl($str) {
    $dom = new Crawler();

    $dom->addHtmlContent($str, 'UTF-8');
    $res = $dom->filter('div.overflow')->filter('div.leftW')->filter('div.borderTno')->filter('table')->html();

    return $res;
}

function getDetail($str){

    $dom = new Crawler();

    $dom->addHtmlContent($str, 'UTF-8');

    $title = $dom->filter('div.container_lf')->filter('div.mian_title > h2')->text();

    $contents = $dom->filter('div.container_lf')->filter('div.content > p')->each(function (Crawler $node, $i) {
        return $node->text();
    });

    $data = $dom->filter('div.container_lf')->filter('div.mian_title')->filter('ul.subctitle > li')->each(function (Crawler $node, $i) {
        if ($i == 0||$i == 1) {
            return $node->text();
        }
    });

    $s_time = substr($data[0],9);
    $info_from = substr($data[1],9);

    $content = "<style>p {text-indent:2em;line-height: 1.75em;font-family: \'微软雅黑\', \'Microsoft YaHei\'; font-size: 14px;}</style>";

    foreach($contents as $k =>$v) {
    $content .= "<p>".$v."</p>";
    }
        $arr = [
            'title'=>$title,
            'content' => $content,
            'time' => $s_time,
            'info_from' => $info_from
        ];

        return $arr;
    }

//获取当天可用链接
function getTodayInfo($str) 
{
    $dom = new Crawler();

    $dom->addHtmlContent($str, 'UTF-8');

    $time = $dom->filter('font')->text();

    $url = $dom->filter('a')->attr('href');
    $res = [
        'time'=>$time,
        'url'=>$url
    ];
    return $res;
}
// 插入数据
function insertData($data)
{
   try {
    $connect=mysqli_connect(MYSQL_CONF['host'],MYSQL_CONF['user'],MYSQL_CONF['psd'],MYSQL_CONF['dbname']);
    
    $create_time = date('Y-m-d h:s:i');
    $main_time = $data['maintime'];
    $title = $data['title'];
    $url = $data['url'];
    $content = $data['content'];
    $time = $data['time'];
    $info_from = $data['info_from'];
    
    $sql = "insert into news_data(create_time,maintime,title,url,content,time,info_from) values('$create_time',' $main_time',' $title ','$url','$content',' $time','$info_from')";
    mysqli_query($connect,'set names utf8');
    $result=mysqli_query($connect,$sql);

    return $result; 
   } catch (\Exception $e) {
        throw new \Exception($e->getMessage());
   }

}

