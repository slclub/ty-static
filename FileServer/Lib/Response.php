<?php
namespace FS\Lib;
class Response{

	

	public $_rs = [];

	public function __construct()
	{
		$this->_rs['code'] = 200;
		$this->_rs['info'] = 'ok';
	}

	public function writeWithArray($arr){
		$this->_rs = array_merge($this->_rs, is_array($arr)? $arr:[]);
	}

	public function writeWith($key, $value){
		if (!$key) {
			return ;
		}

		$this->_rs[$key] = $value;
	}

	public function _echo(){
		echo json_encode($this->_rs, JSON_UNESCAPED_UNICODE);
		exit;
	}

}