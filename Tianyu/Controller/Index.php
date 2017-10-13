<?php

namespace TY\Controller;
use TY\Extension\Lib\FileStore;

class Index extends \FS\Core\Controller{

	public function sysupload(){
		$streamData = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';  
  
	    if(empty($streamData)){  
	        $streamData = file_get_contents('php://input');  
	    }

	    $store = new FileStore;
	    
	    if($streamData!=''){  
	    	
	    	$info = explode(FileStore::delimiter(), $streamData);
	    	$ext = $store->decodeFile($info[1], 'extension');

	    	if ($err = \checkErr($ext)) {
	    		return $this->Response->writeWithArray($err);
	    	}
	    	$filename = $store->getAttachFilePath(null, $ext);
	    	header("Content-type:text/html;charset=utf-8");
	        $ret = file_put_contents($filename, $info[0], true);  
	    }else{  
	        $this->Response->writeWith('info', 'file is empty, upload fail ');
	        $this->Response->writeWith('code', '1200');
	        
	    }  
  		
  		file_put_contents('/tmp/static.log', print_r([$filename, $streamData, $ret, date('m-d H:i:s')],1), 8);
		$this->Response->writeWithArray(FileStore::getWebFileInfo($filename));
	}
    
}