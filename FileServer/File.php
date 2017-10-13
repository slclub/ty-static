<?php
namespace FS;

require(dirname(__FILE__).'/Constant.php');

require('FileAutoload.php');

class File{

	protected static $_app;

	public static function app()
	{
		if (!(self::$_app instanceof self)) {
			self::$_app = new self;
		}

		return self::$_app;
	}

	public function run($space)
	{
		$router = new \FS\Lib\Router();
		$router->setSpace($space);
		$router->build();
		$router->run();
	}
}