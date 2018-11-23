<?php

namespace TY\Controller;
use TY\Extension\Lib\FileStore;

class Index extends \FS\Core\Controller{

    public function index(){
    }

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
			file_put_contents('/tmp/static.log', print_r([$filename, $streamData, $ext, date('m-d H:i:s')],1), 8);
	    		return $this->Response->writeWithArray($err);
	    	}
            $tempfilename = $store->decodeFile($info[1], 'filename');
            if(strpos($tempfilename,"maiDian") > 0)
                $filename = $store->getAttachFilePath($tempfilename, $ext);
            else
                $filename = $store->getAttachFilePath(null, $ext);
	    	header("Content-type:text/html;charset=utf-8");
	        $ret = file_put_contents($filename, $info[0], true);  
            $logfileName = $store->getAttachFilePath("maidianlog.txt", $ext);
            file_put_contents($logfileName, $tempfilename.PHP_EOL, FILE_APPEND);
	    }else{  
	        $this->Response->writeWith('info', 'file is empty, upload fail ');
	        $this->Response->writeWith('code', '1200');
	        
	    }  
  		
  		file_put_contents('/tmp/static.log', print_r([$filename, $streamData, $ret, date('m-d H:i:s')],1), 8);
		$this->Response->writeWithArray(FileStore::getWebFileInfo($filename));
	}

    public function up_package()
    {
        $html = '<html>
<body>

<form method="post"
enctype="multipart/form-data" action="/up_package_upload">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="点我啊点我啊" />
</form>

</body>
</html>';
        echo $html;
        echo APP_PATH;
    }

    public function up_package_upload(){
        
        $base_upload_apk_path = APP_PATH."/data/files/package_apk/";
        if ($_POST['floder']){
            $base_upload_apk_path .= $_POST['floder'].'/';
        }

        if ($_FILES["file"]["error"] > 0)
        {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
        }
        else
        {
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

            if (file_exists("upload/" . $_FILES["file"]["name"]))
            {
                echo $_FILES["file"]["name"] . " already exists. ";
            }
            else
            {
                move_uploaded_file($_FILES["file"]["tmp_name"],$base_upload_apk_path . $_FILES["file"]["name"]);
            }
        }

        echo "2秒后！马上跳转";
        sleep(2);
        Header("Location:http://static.tianyuonline.cn/files/package_apk/");//直接跳转
    }
    
}
