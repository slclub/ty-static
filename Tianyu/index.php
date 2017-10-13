<?php

include('include.php');
ini_set('display_errors', '1');
ini_set("error_reporting", E_ALL & ~E_NOTICE & ~E_STRICT);

\FS\File::app()->run(SPACE);