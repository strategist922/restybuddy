<?php

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
 * Statistics
 * @param string $key  set stat key
 * @param int $step per step num
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
 * statics run time
 * @param string $start start time
 * @param string $end  end time
 * @param int $dec  float dec size
 */
function G($start, $end = '', $dec = 3) {
	static $_info = array();
	if (!empty($end)) {
		if (!isset($_info[$end])) {
			$_info[$end] = microtime(TRUE);
		}
		return number_format(($_info[$end] - $_info[$start]), $dec);
	} else {
		$_info[$start] = microtime(TRUE);
	}
}




function debug($mix) {
	error_log(var_export($mix,true));
}



function posix_getpid_new()
{
	return DIRECTORY_SEPARATOR == '/' ? posix_getpid() : '33333';
}


function sbcToDbc($strString) {
	$DBC = Array(
			'０', '１', '２', '３', '４',
			'５', '６', '７', '８', '９',
			'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ',
			'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ',
			'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
			'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ',
			'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ',
			'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ',
			'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
			'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ',
			'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ',
			'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ',
			'ｙ', 'ｚ', '－', '　', '：',
			'．', '，', '／', '％', '＃',
			'！', '＠', '＆', '（', '）',
			'＜', '＞', '＂', '＇', '？',
			'［', '］', '｛', '｝', '＼',
			'｜', '＋', '＝', '＿', '＾',
			'￥', '￣', '｀'
	);
	$SBC = Array(
			'0', '1', '2', '3', '4',
			'5', '6', '7', '8', '9',
			'A', 'B', 'C', 'D', 'E',
			'F', 'G', 'H', 'I', 'J',
			'K', 'L', 'M', 'N', 'O',
			'P', 'Q', 'R', 'S', 'T',
			'U', 'V', 'W', 'X', 'Y',
			'Z', 'a', 'b', 'c', 'd',
			'e', 'f', 'g', 'h', 'i',
			'j', 'k', 'l', 'm', 'n',
			'o', 'p', 'q', 'r', 's',
			't', 'u', 'v', 'w', 'x',
			'y', 'z', '-', ' ', ':',
			'.', ',', '/', '%', '#',
			'!', '@', '&', '(', ')',
			'<', '>', '"', '\'', '?',
			'[', ']', '{', '}', '\\',
			'|', '+', '=', '_', '^',
			'$', '~', '`'
	);
	return str_replace($DBC, $SBC, $strString);
}


function getFileContentToArray($file)
{

	$array = array();
	if(is_file($file)) {

		$file_handle = fopen($file,"r");
		while(!feof($file_handle)) {
			$line = fgets($file_handle);
			$line = trim($line);
			if(!empty($line)) {
				$array[] = $line;
			}
		}
		fclose($file_handle);
	}
	return $array;
}



// get html dom from file
// $maxlen is defined in the code as PHP_STREAM_COPY_ALL which is defined as -1.
function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = 'UTF-8', $stripRN=true, $defaultBRText="\r\n")
{
	// We DO force the tags to be terminated.
	$dom = new Crawler_Simplehtmldom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
	// For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
	$contents = file_get_contents($url, $use_include_path, $context, $offset);
	// Paperg - use our own mechanism for getting the contents as we want to control the timeout.
	//    $contents = retrieve_url_contents($url);
	if (empty($contents))
	{
		return false;
	}
	// The second parameter can force the selectors to all be lowercase.
	$dom->load($contents, $lowercase, $stripRN);
	return $dom;
}

// get html dom from string
function str_get_html($str, $lowercase=true, $forceTagsClosed=true, $target_charset = "UTF-8", $stripRN=true, $defaultBRText="\r\n")
{
	$dom = new Crawler_Simplehtmldom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
	if (empty($str))
	{
		$dom->clear();
		return false;
	}
	$dom->load($str, $lowercase, $stripRN);
	return $dom;
}

// dump html dom tree
function dump_html_tree($node, $show_attr=true, $deep=0)
{
	$node->dump($node);
}


function objid($length = 1) {
	$ivan_len = $length;
	$time = explode(' ', microtime());
	$id = $time[1] . sprintf('%06u', substr($time[0], 2, 6));
	if ($ivan_len > 0) {
		$id .= substr(sprintf('%010u', mt_rand()), 0, $ivan_len);
	}
	return $id;
}

function K($name = null, $value = null) {
	static $_keys = array();
	//if empty get all
	if (empty($name)) {
		return $_keys;
	}
	//set value first
	if (is_string($name)) {
		if (!strpos($name, '.')) {
			$name = strtolower($name);
			if (is_null($value)) {
				return isset($_keys[$name]) ? $_keys[$name] : null;
			}
			$_keys[$name] = $value;
			return;
		}
		//get and set array
		$name = explode('.', $name);
		$name[0] = strtolower($name[0]);
		if (is_null($value)) {
			return isset($_keys[$name[0]][$name[1]]) ? $_keys[$name[0]][$name[1]] : null;
		}
		$_keys[$name[0]][$name[1]] = $value;
		return;
	}
	//array set
	if (is_array($name)) {
		return $_keys = array_merge($_keys, array_change_key_case($name));
	}
	return null;
}

//json return
function jsonReturn($code,$data='',$info='')
{
	header("Content-Type:text/html;charset:utf-8");
	$return['code'] = $code;
	$return['data'] = $data;
	//if code !== 0 then get error info
	$return['info'] = $info;
	exit(json_encode($return));
}


function Q($class, $paramNode = '', $method = '') {
	static $_objects = array();
	$identify = $class . $method;
	if (!isset($_objects[$identify])) {
		if (class_exists($class)) {
			$o = empty($paramNode) ? new $class() : new $class($paramNode);
			if (!empty($method) && method_exists($o, $method)) {
				return $o->$method();
			} else {
				$_objects[$identify] = $o;
			}
		} else {
			throw new Exception("$class is not find!");
		}
	}
	return $_objects[$identify];
}



/**
 * Cookie set/get/clear
 * 1 cookie: cookie('name')
 * 2 clear cookie: cookie(null)
 * 3 del prifix cookie: cookie(null,'think_') | prefix
 * 4 set cookie: cookie('name','value') | savetime: cookie('name','value',array('expire'=>36000))
 * 5 del cookie: cookie('name',null)
 * $option prefix,expire,path,domain
 * cookie('name','value',array('expire'=>1,'prefix'=>'think_'))
 * cookie('name','value','prefix=tp_&expire=10000')
 */
function cookie($name, $value = '', $option = null) {
	$config = array(
			'prefix' => '',
			'expire' => '2286400',
			'path' => '/',
			'domain' => '',
	);
	if (!empty($option)) {
		if (is_numeric($option)) {
			$option = array('expire' => $option);
		} elseif (is_string($option)) {
			parse_str($option, $option);
		}
		$config = array_merge($config, array_change_key_case($option));
	}
	if (is_null($name)) {
		if (empty($_COOKIE)) {
			return;
		}
		$prefix = empty($value) ? $config['prefix'] : $value;
		if (!empty($prefix)) {
			foreach ($_COOKIE as $key => $val) {
				if (0 === stripos($key, $prefix)) {
					setcookie($key, '', time() - 3600, $config['path'], $config['domain']);
					unset($_COOKIE[$key]);
				}
			}
		}
		return;
	}
	$name = $config['prefix'] . $name;
	if ('' === $value) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	} else {
		if (is_null($value)) {
			setcookie($name, '', time() - 3600, $config['path'], $config['domain']);
			unset($_COOKIE[$name]);
		} else {
			$expire = !empty($config['expire']) ? time() + intval($config['expire']) : 0;
			setcookie($name, $value, $expire, $config['path'], $config['domain']);
			$_COOKIE[$name] = $value;
		}
	}
}

function getMultiKeys($prefix,$arr)
{
	$keys = array();
	foreach ($arr as $one)
	{
		$keys[] = $prefix.':'.$one;
	}
	return $keys;
}


/**
 * add params to url
 * @param string $strUrl
 * @param array $arrParamsToAdd array('key'=>'value','key1'=>'value1')
 */
function addParamToUrl($strUrl, $arrParamsToAdd = array()) {
	$strQuery = substr(strstr($strUrl, "?"), 1); // string or false
	$arrQueryParams = array();
	if ($strQuery) {
		$strFragment = strstr($strQuery, "#");      // string or false
		$strQuery = $strFragment ? substr($strQuery, 0, strlen($strQuery) - strlen($strFragment)) : $strQuery; // delete the fragment from the query

		$arrUrlParse["query"] = $strQuery;
		parse_str($strQuery, $arrQueryParams);
	}
	$arrUrlParse = parse_url($strUrl);
	if (empty($arrUrlParse["path"]) && empty($strQuery)) {
		$arrUrlParse["path"] = '/';
	}
	$arrQueryParams = array_merge($arrQueryParams, $arrParamsToAdd);
	$arrUrlParse['query'] = http_build_query($arrQueryParams);
	$url = (isset($arrUrlParse["scheme"]) ? $arrUrlParse["scheme"] . "://" : "") .
	(isset($arrUrlParse["user"]) ? $arrUrlParse["user"] . ":" : "") .
	(isset($arrUrlParse["pass"]) ? $arrUrlParse["pass"] . "@" : "") .
	(isset($arrUrlParse["host"]) ? $arrUrlParse["host"] : "") .
	(isset($arrUrlParse["port"]) ? ":" . $arrUrlParse["port"] : "") .
	(isset($arrUrlParse["path"]) ? $arrUrlParse["path"] : "") .
	(isset($arrUrlParse["query"]) ? "?" . $arrUrlParse["query"] : "") .
	(isset($arrUrlParse["fragment"]) ? "#" . $arrUrlParse["fragment"] : "");
	return $url;
}

