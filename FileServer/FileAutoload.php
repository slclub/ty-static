<?php

class FileAutoload{
	public static function load($class)
	{
		$phpFile = '';
		$arr = explode("\\", $class);
		if (count($arr) <=0) {
			return ['code'=>1404, 'msg' => 'class not fonud'];
		}

		switch ($cc = count($arr)) {
			case $cc<= 0:
				# code...
				break;
			case 1:

				break;
			default:
				$first = array_shift($arr);
				$phpFile = FS_ROOT . DS . implode(DS, $arr) . '.php';				
				# code...
				break;
		}
		
		if (!file_exists($phpFile)){
			return;
		}
		include_once($phpFile);
	}
}

spl_autoload_register(['FileAutoload', 'load']);