<?php  
/** php 发送流文件 
* @param  String  $url  接收的路径 
* @param  String  $file 要发送的文件 
* @return boolean 
*/  
function sendStreamFile($url, $file){  
  
    if(file_exists($file)){  
        $info = pathinfo($file);

        $opts = array(  
            'http' => array(  
                'method' => 'POST',  
                'header' => 'content-type:application/x-www-form-urlencoded',  
                'content' => file_get_contents($file).'####' . json_encode($info, JSON_UNESCAPED_UNICODE),
            )  
        );  
  
        $context = stream_context_create($opts);  
        $response = file_get_contents($url, false, $context);  
        $ret = json_decode($response, true);  
        return $ret;  
  
    }else{  
        return false;  
    }  
  
}  

//$ret = sendStreamFile('http://static.tianyuonline.cn/index/sysupload', dirname(__FILE__) .'/./../data/send.txt');  
$ret = sendStreamFile('http://static.tianyuonline.cn/index/sysupload', dirname(__FILE__) .'/./../data/favicon.ico');  
var_dump($ret);  