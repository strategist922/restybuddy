<?php

/**
* entry for project
*/

define("APP_PATH",  dirname(__FILE__));
require_once dirname(dirname(APP_PATH))."/Env/env.inc.php";


define("RUN_MODE",'product');

ini_set('yaf.cache_config',1);

$app  = new Yaf_Application(CONF_PATH."/".RUN_MODE. "/application.ini");

$app->bootstrap()->run();