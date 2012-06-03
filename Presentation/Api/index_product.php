<?php
/**
* entry for project
*/
define("APP_PATH",  dirname(__FILE__));
define("RUN_MODE",'product');
require_once dirname(dirname(APP_PATH))."/Env/env.inc.php";
ini_set('yaf.cache_config',1);
$app  = new Yaf_Application(CONF_PATH."/application.ini");
$app->bootstrap()->run();
