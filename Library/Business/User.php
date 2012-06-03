<?php

class Business_User {

	protected $_params;
	protected $_redis;
	protected $_fields;

	public function __construct($params)
	{
		$this->_params = $params;
		$this->_params['lat'] = isset($this->_params['lat']) ? $this->_params['lat'] : '';
		$this->_params['long'] = isset($this->_params['long']) ? $this->_params['long'] : '';
		$this->_redis = Sharding_Redis::instance();
	}

	public function existUser($set = false)
	{
		$screenname = trim($this->_params['screenname']);
		$email = trim($this->_params['email']);
		$md5screename = md5($screenname);
		$md5email = md5($email);
		$keyscreenname = K('user.checkscreenname').':'.$md5screename;
		$keyemail = K('user.checkemail').':'.$md5email;
		if($set) {
			$this->_redis->set($keyscreenname,$screenname);
			$this->_redis->set($keyemail,$email);
			return true;
		}
		if($this->_redis->get($keyscreenname) || $this->_redis->get($keyemail))
		{
			return true;
		}
		return false;
	}

	public function setUser()
	{
		if(!$this->existUser()) {
			$this->_params['password']= md5($this->_params['password']);
			//add more field
			$this->_params['remark'] = '';
			$this->_params['domain'] = '';
				
			$this->_params['id'] = objid();
			$r = Sharding_Redis::objectSet(K('user.user'), $this->_params,$this->_params['id']);
			if($r) {
				$eventkey = K('event.user');
				Sharding_Redis::objectSet($eventkey, $this->_params['id']);
				$this->existUser(true);
				return true;
			}
		}
		return false;
	}


	public function getUser()
	{
		//@todo add more field to display
		$field = array('id','email','screenname');
		return $this->_redis->hmGet(K('user.user').':'.$this->_params['id'],$field);
	}

	public function domainUser()
	{
		$domain = $this->_params['domain'];
		$id = $this->_redis->get(K('user.domain').':'.$domain);
		if(intval($id)){
			$this->_params['id'] = $id;
			return $this->getUser();
		}
		return false;
	}

	public function countsUser()
	{
		$count['follower'] = $this->_redis->lSize(K('relation.follower').':'.$this->_params['id']);
		$count['following'] = $this->_redis->lSize(K('relation.following').':'.$this->_params['id']);
		return $count;
	}

	public function infoUser()
	{
		$this->_fields = array('email','screenname');

	}

	public function uploadUser()
	{

	}

	public function remarkUser()
	{
		//
		
	}

	public function signUser()
	{
		$sign = $this->_params['sign'];
		$uid = $this->_params['uid'];
		return $this->_redis->hmSet(K('user.user').':'.$uid,'sign',$sign);
	}
	
}