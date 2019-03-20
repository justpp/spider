<?php
  function cUrlDo($url, $header = '', $agent='',$data = null, $type = 'string')
    {
        $referurl = "http://www.21cp.com";
        //初始化curl
        $curl = curl_init();
        //设置cURL传输选项
        if (is_array($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//不直接输出打印
        curl_setopt($curl, CURLOPT_URL, $url);//设置请求路径
        curl_setopt($curl, CURLOPT_REFERER, $referurl);  //模拟来源网址  
        curl_setopt($curl, CURLOPT_USERAGENT, $agent); //模拟常用浏览器的useragent  
        //https请求添加这两句话，跳过证书验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    
        if (!empty($data)) {//post方式
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        //获取采集结果
        $output = curl_exec($curl);
        //关闭cURL链接
        curl_close($curl);
        $output = mb_convert_encoding($output, 'utf-8', 'GBK,UTF-8,ASCII');
        if ($type == 'json') {
            //解析json
            $json = json_decode($output, true);
            return $json;
        } elseif ($type == 'xml') {
            #验证xml
            libxml_disable_entity_loader(true);
            #解析xml
            $xml = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
            return $xml;
        } else {
            return $output;
        }
        
    }

    function cUrl($url) {
        $ip = "101.81.205.49";
        $header = array(  
            'CLIENT-IP:'.$ip,  
            'X-FORWARDED-FOR:'.$ip,  
        );    //构造ip  
        $useragent = array(
                    'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
                    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1',
                    'Opera/9.27 (Windows NT 5.2; U; zh-cn)',
                    'Opera/8.0 (Macintosh; PPC Mac OS X; U; en)',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13 ',
                    'Mozilla/5.0 (Windows; U; Windows NT 5.2) AppleWebKit/525.13 (KHTML, like Gecko) Version/3.1 Safari/525.13'
        
                );
        return cUrlDo($url,$header,array_rand($useragent));
    }


    function keepinsql($data)
    {
       
        $connect=mysqli_connect('localhost','root','123','tnews');

        
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
        
       
}
    
        