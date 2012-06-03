<?php
/*
* entry for project
*/

define("RUN_MODE",'dev');
define("APP_PATH",  dirname(__FILE__));
require_once dirname(dirname(APP_PATH))."/Env/env.inc.php";
$app  = new Yaf_Application(CONF_PATH."//application.ini");
$app->bootstrap()->run();
