<?php

//environment for project

//define app name
define("APP_NAME","restybuddy");
//define home path 
define("HOME_PATH",'/source');
//run mode
define("RUN_MODE",'dev');
//difine root path for project
define("ROOT_PATH",HOME_PATH.'/'.APP_NAME);
//define config path
define("CONF_PATH",ROOT_PATH.'/conf/'.RUN_MODE);
//define log path
define("LOG_PATH",ROOT_PATH.'/log');
//define lib path
define("LIB_PATH",ROOT_PATH.'/library');
//define temp path
define("TEMP_PATH",APP_PATH.'/temp');

error_reporting(E_ALL);

mb_internal_encoding("UTF-8");

ini_set('memory_limit','1024M');

ini_set('yaf.library',LIB_PATH);

//load common functions
require_once dirname(__FILE__)."/functions.php";

//set vender document
define("VENDER_PATH",LIB_PATH."/Vender");
//aotoload file in verder_path
set_include_path(get_include_path().":".VENDER_PATH);

