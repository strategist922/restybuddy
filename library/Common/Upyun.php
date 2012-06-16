<?php
// +----------------------------------------------------------------------
// | Buddy Framework
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://buddy.woshimaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xinqiyang <xinqiyang@gmail.com>
// +----------------------------------------------------------------------
/**
 * Image Upload service
 * use upaiyun.com cloud service
 * @author xinqiyang
 *
 */
class Common_Upyun
{
	protected $_config = array();
	protected $_params = '';
	//have 2 type : image or file
	public function __construct($params)
	{
		$this->_config = C($params);
		$this->_params = $params;
		if(empty($this->_config)){
			throw new Exception(__CLASS__." $params config node not find!");
		}
	}

	/**
	 * update file to upyun.com
	 * @param string $object
	 * @param string $filename
	 * @param string $path
	 */
	public function putFile($path,$filename,$object='')
	{
		error_log($path."   ".$filename);
		$object = empty($object) ? $this->_params.'/' : $object.'/';
		$postField = file_get_contents((realpath($path)));
		$process = curl_init($this->_config['api'].'/'.$this->_config['bucketname'].'/'.$object.$filename);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $postField);
		curl_setopt($process, CURLOPT_USERPWD, $this->_config['username'].':'.$this->_config['userpass']);
		curl_setopt($process, CURLOPT_HTTPHEADER, array('Expect:', "Mkdir:true"));
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($process);
		$code = curl_getinfo($process, CURLINFO_HTTP_CODE);
		curl_close($process);
		if($code == 200) {
			logNotice(__CLASS__.": $path  $filename  $object  ok ".$this->_config['api'].'/'.$this->_config['bucketname'].'/'.$object.$filename);
			//unlink the file
			//unlink($path);
			return true;
		}
		logNotice(__CLASS__.": $path  $filename  $object  error ".$this->_config['api'].'/'.$this->_config['bucketname'].'/'.$object.$filename);
		return false;
	}

	public  function getFile($filename,$object='')
	{
		$object = empty($object) ? $this->_params.'/' : $object.'/';
		$process = curl_init($this->_config['api'].'/'.$this->_config['bucketname'].'/'.$object.$filename);
		curl_setopt($process, CURLOPT_USERPWD, $this->_config['username'].':'.$this->_config['userpass']);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($process);
		$code = curl_getinfo($process, CURLINFO_HTTP_CODE);
		curl_close($process);
		if($code == 200) {
			return true;
		}
		logNotice(__CLASS__.": $filename  $object  del error");
		return false;
	}

	public  function delFile($filename,$object='')
	{
		$object = empty($object) ? $this->_params : $object.'/';
		$process = curl_init($this->_config['api'].'/'.$this->_config['bucketname'].'/'.$object.$filename);
		curl_setopt($process, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($process, CURLOPT_USERPWD, $this->_config['username'].':'.$this->_config['userpass']);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($process);
		$code = curl_getinfo($process, CURLINFO_HTTP_CODE);
		curl_close($process);
		if($code == 200) {
			return true;
		}
		logNotice(__CLASS__.": $filename  $object  del error");
		return false;
	}

	public  function usage()
	{
		$process = curl_init($this->_config['api'].'/'.$this->_config['bucketname'].'?usage');
		curl_setopt($process, CURLOPT_USERPWD, $this->_config['username'].':'.$this->_config['userpass']);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($process);
		$code = curl_getinfo($process, CURLINFO_HTTP_CODE);
		curl_close($process);
		return $result;
	}

}
