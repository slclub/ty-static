<?php

include('include.php');
ini_set('display_errors', '1');
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT);
ini_set("max_execution_time",1800);

\FS\File::app()->run(SPACE);
