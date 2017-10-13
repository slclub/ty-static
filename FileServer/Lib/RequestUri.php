<?php
namespace FS\Lib;

class RequestUri{

	protected $_uri = '';

	protected $_controller = '';

	protected $_action = '';

	public function request()
	{
		$this->_uri = $_SERVER['REQUEST_URI'];

		$this->_controller = dirname($this->_uri);

		$arr = explode(DS, $this->_controller);
		foreach ($arr as $key => &$value) {
			$value = ucfirst($value);
		}

		$this->_controller = implode('\\', $arr);

		$this->_controller = str_replace(DS, "\\", $this->_controller);
		(!$this->_controller || $this->_controller == DS) && $this->_controller = '\\Index';

		$this->_controller = "\\Controller". $this->_controller;

		$this->_action = basename($this->_uri);
		!$this->_action && $this->_action = 'index';
	}

	public function getController(){
		return $this->_controller;
	}

	public function getAction(){
		return $this->_action;
	}
}