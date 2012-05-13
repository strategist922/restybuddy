<?php

//environment for project

//define app name
define("APP_NAME","searchengine");
//define home path 
define("HOME_PATH",'/source');
//difine root path for project
define("ROOT_PATH",HOME_PATH.DIRECTORY_SEPARATOR.APP_NAME."/trunk");
//define config path
define("CONF_PATH",ROOT_PATH.DIRECTORY_SEPARATOR."Conf");
//define log path
define("LOG_PATH",ROOT_PATH.DIRECTORY_SEPARATOR."Log");
//define lib path
define("LIB_PATH",ROOT_PATH.DIRECTORY_SEPARATOR."Library");

error_reporting(E_ALL);

mb_internal_encoding("UTF-8");

ini_set('memory_limit','1024M');

ini_set('yaf.library',LIB_PATH);



//add more useful functions 

/**
 * get configuration fields
 * @param string $name paramname
 * @param mixed $value param value
 */
function C($name = null, $value = null) {
  static $_config = array();
  //if empty get all
  if (empty($name)) {
    return $_config;
  }
  //set value first
  if (is_string($name)) {
    if (!strpos($name, '.')) {
      $name = strtolower($name);
      if (is_null($value)) {
        return isset($_config[$name]) ? $_config[$name] : null;
      }
      $_config[$name] = $value;
      return;
    }
    //get and set array
    $name = explode('.', $name);
    $name[0] = strtolower($name[0]);
    if (is_null($value)) {
      return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : null;
    }
    $_config[$name[0]][$name[1]] = $value;
    return;
  }
  //array set
  if (is_array($name)) {
    return $_config = array_merge($_config, array_change_key_case($name));
  }
  return null; //return null if get the no exist param name
}

/**
 * 设置和获取统计数据
 * @param string $key  统计的key
 * @param int $step 增加多少
 */
function N($key, $step = 0) {
  static $_num = array();
  if (!isset($_num[$key])) {
    $_num[$key] = 0;
  }
  if (empty($step)) {
    return $_num[$key];
  } else {
    $_num[$key] = $_num[$key] + (int) $step;
  }
}





/**
 * 统计时间方法
 * @param string $start 开始时间
 * @param string $end
 * @param int $dec
 */
function G($start, $end = '', $dec = 3) {
  static $_info = array();
  if (!empty($end)) { // 统计时间
    if (!isset($_info[$end])) {
      $_info[$end] = microtime(TRUE);
    }
    return number_format(($_info[$end] - $_info[$start]), $dec);
  } else { // 记录时间
    $_info[$start] = microtime(TRUE);
  }
}




function debug($mix) {
    error_log(var_export($mix,true));
}







