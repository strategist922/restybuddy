<?php

class Business_Remind {
	
	protected $_params = array();
	protected $_redis = '';
	public function __construct($params)
	{
		$this->_params = $params;
		$this->_redis = Sharding_Redis::instance();
	}
	
	public function unread()
	{
		$key = K('remind.unread').':'.$this->_params['id'];
		return $this->_redis->zRange($key,0,-1,true);
	}
	
	public function setcountZero()
	{
		$key = K('remind.unread').':'.$this->_params['id'];
		return $this->_redis->zAdd($key,0,$this->_params['type']);
	}
	
	
	public function setcount()
	{
		$key = K('remind.unread').':'.$this->_params['id'];
		return $this->_redis->zIncrBy($key,$this->_params['score'],$this->_params['type']);
	}
}