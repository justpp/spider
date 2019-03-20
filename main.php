<?php
require_once './curl.php';
require_once './spider.php';

function urlSpider($url)
{
    $html = cUrl($url);
    $res = getUrl($html);
    $resArrs = explode('·',$res);
    $arr = [];

    foreach ($resArrs as $k => $v) {
        if ($k>0) {
            $data = getTodayInfo($v);
            $date_time = date('m-d');
            if ($data['time'] == $date_time) {
                $arr[] = $data;
            }else{
                echo '不是今天的新闻,跳过'."\n";
                continue;
            }
        }
    }

    $news_data = [];
    $bool = false;
    foreach ($arr as $k => $v ) {
        $news_info = getDetail(cUrl($v['url']));
        $create_time = date('Y-m-d H:i:s');
        $main_time = $v['time'];
        $news_data[$k] = [
            'create_time' => $create_time,
            'maintime' => $main_time,
            'title' => $news_info['title'],
            'url' => $v['url'],
            'content' => $news_info['content'],
            'time' => $news_info['time'],
            'info_from' => $news_info['info_from']
        ];
        $bool = insertData($news_data[$k]);

    }
    return $bool;
}