<?php
namespace TY\Extension\Lib;
class FileStore{

	public function uniqid()
	{
		return uniqid();
	}

	public function attachBasePath()
	{
		return APP_PATH . DS . 'data' . DS . 'files';
	}

	public function getAttachFilePath($file = null, $ext = null){

		if (!$file) {
			$file = $this->uniqid();
		}

		$ext && $file .= '.' . $ext;

		$realFile = $this->attachBasePath(). DS . self::timeFloderName() . DS . $file;
		self::mk_dir(dirname($realFile));
		return $realFile;
	}

	public static function delimiter()
	{
		return '####';
	}

	public static function decodeFile($json = '', $op = 'basename')
	{
		$info = json_decode($json, true);

		//language
		if (count($info) < 3) {
			return ['code' => '1211', 'msg' => '传输的文件信息不全, 必须同时有 {filename,basename,extension}'];
		}

		if (!isset($info['filename']) || !$info['filename']) {
			return ['code' => '1212', 'msg' => '文件名(不带扩展名) 不存在或为空'];
		}

		if (!isset($info['basename']) || !$info['basename']) {
			return ['code' => '1212', 'msg' => '全文件名(带扩展名) 不存在或为空'];
		}

		if (!isset($info['extension']) || !$info['extension']) {
			return ['code' => '1212', 'msg' => '文件扩展名 不存在或为空'];
		}

		switch ($op) {
			// 返回原文件名
			case 'basename':
				return $info['basename'];
				break;

			// 返回文件名 不带扩展名
			case 'filename':
				return $info['filename'];
				break;
			case 'extension':
				return $info['extension'];
				break;
			default:
				return $info;
				break;
		}
	}

	public static function getWebFileInfo($file){
		$relPath = str_replace(APP_PATH, '', $file);
		$host = $_SERVER['HTTP_HOST'];
		$url = $host . $relPath;

		$info['filename'] = $relPath;
		$info['host'] = $host;
		$info['url'] = $url;
		return $info;
	}

	//
	public static function mk_dir($dir, $mode = 0755) 
	{ 
		if(is_dir($dir) || @mkdir($dir,$mode)){ 
			//echo $dir."创建成功<br>";  
		}else{
			self::mk_dir(dirname($dir), $mode);
			if(@mkdir($dir,$mode)){
				//echo $dir."创建成功<br>";
			}
		}
	}

	public static function timeFloderName(){

		return date('Y/m/d');
	}
}