<?php
namespace FS\Core;
class Controller{

	public $Response;

	public function __construct()
	{
		$this->Response = new \FS\Lib\Response;
	}

	public function write(){
		header("Content-type:text/html;charset=utf-8");
		$this->Response->_echo();
	}
}