<?php

namespace FS\Lib;

class Router{

	protected $_space;

	protected $_rootPath = '';

	protected $_controller = '';

	protected $_action = '';

	protected $_obj_ctrl;

	public function setSpace($space)
	{
		$this->_space = $space;
	}

	public function build(){
		$obj = new RequestUri();

		$obj->request();
		$this->_controller = $obj->getController();
		$this->_action = $obj->getAction();

		$this->_controller = '\\'.$this->_space. $this->_controller;
	}

	public function run()
	{
		$this->_obj_ctrl = new $this->_controller;
		$this->_obj_ctrl->{$this->_action}();

		$this->_obj_ctrl->write();
	}
}