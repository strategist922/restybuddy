<?php


class Count_Deal {
	
	protected $_source;
	protected $_redis;
	
	public function __construct($source='') 
 	{
		$this->_source = strtolower($source);
		$this->_redis = Sharding_Redis::instance('count');
	}

	//@TODO CHANGE KEYS FROM KEY.INI
	public function setQueue()
	{
	    $args = func_get_args();
	    if(!empty($args)) {
	    //define the list of source
	    $key = 'c:l:'.$this->_source;
	    //add to source
	    error_log($key);
	    $this->_redis->lPush($key,json_encode($args));	
	   }
	   return ;
	}
	
	public function getQueue()
	{
	    $key = 'c:l:'.$this->_source;
	    error_log($key);
	    return $this->_redis->lPop($key); 
	}



}

