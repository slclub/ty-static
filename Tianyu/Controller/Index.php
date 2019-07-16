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
<script src="https://cdn.bootcss.com/jquery/3.2.0/jquery.min.js"></script>
<body>
<form method="post"
enctype="multipart/form-data" action="/up_package_upload">

    <div class="fileUpload btn btn-primary">
        <input id="uploadFile" placeholder="Choose File" disabled="disabled" />
        <span class="uploadBtn">UPLOAD</span>
        <input id="file" name="file" type="file" class="upload" />
    </div>
<div class="progress">
    <div></div>
</div>
<br />
<input class="upload_btn_ajax uploadBtn"  type="button" name="submit" value="SUBMIT" />
<br />
<!--
<input class="uploadBtn" type="submit" name="submit" value="SUBMIT" />
-->
</form>
<pre id ="ajax_return">
----------------------------------------------------------------------------------
    访问地址:http://static.tianyuonline.cn/stable/<span id="pre_file_name"></span>

----------------------------------------------------------------------------------
</pre>
</body>
<script type="text/javascript">
    document.getElementById("file").onchange = function () {
        document.getElementById("uploadFile").value = this.value;
    };
$(function() {
    $(".upload_btn_ajax").click(function () {
        //获取上传的文件
        var uploadFileData = $("#file").get(0).files[0];
    
        $("#pre_file_name").html(uploadFileData.name)
        var formdata = new FormData();
    
        formdata.append("file", uploadFileData);
        $.ajax({
            url: "/up_package_upload",
            type: "post",
            dataType: "json",
            processData: false,
            contentType: false,
            data:formdata,
            xhr: function() {
                var xhr = new XMLHttpRequest();
                //使用XMLHttpRequest.upload监听上传过程，注册progress事件，打印回调函数中的event事件
                xhr.upload.addEventListener("progress", function (e) {
                    //console.log(e);
                    //loaded代表上传了多少
                    //total代表总数为多少
                    var progressRate = (e.loaded / e.total) * 100 + "%";
    
                    //通过设置进度条的宽度达到效果
                    $(".progress > div").css("width", progressRate);
                })
    
                return xhr;
            },
            success:function(data){
                $("#ajax_return").html(data)
            }
        })
    });
});
</script>

<style type="text/css" >
#uploadFile {
    width:340px;
    height:30px;
}
.progress {
   width: 600px;
    height: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    margin: 10px 0px;
    overflow: hidden;
}
/* 初始状态设置进度条宽度为0px */
.progress > div {
    width: 0px;     
    height: 100%;
    background-color: yellowgreen;
    transition: all .3s ease;
}
.uploadBtn {
    border:1px solid #ddd; background:#333;color:#eee;
    padding:5px;cursor:pointer;
    font-size:14px;font-weight:bold;
}
.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    left: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
</style>
</html>';
        echo $html;
        //echo APP_PATH;
    }

    public function up_package_upload(){
        
        $base_upload_apk_path = APP_PATH."/data/stable/";
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

        echo "下载地址：http://static.tianyuonline.cn/stable/{$_FILES["file"]["name"]}";
        echo "<br/>2秒后！马上跳转.<br>";
        sleep(10);
        //Header("Location:http://static.tianyuonline.cn/");//直接跳转
    }
    
}


