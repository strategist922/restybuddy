<?php
/*
* entry for project
*/
define("APP_PATH",  dirname(__FILE__));
require_once dirname(dirname(APP_PATH))."/env/env.inc.php";
$app  = new Yaf_Application(CONF_PATH."/application.ini");
$app->bootstrap()->run();

