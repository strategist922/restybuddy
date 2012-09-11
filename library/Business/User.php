<?php

class Business_User {

	protected $_params;
	protected $_redis;
	protected $_fields = array('uid'=>'','email'=>'','screenname'=>'','icon'=>'','domain'=>'','sign'=>'');

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
	
	//set a new user 
	//fields: email,screenname,password,domain
	public function setUser()
	{
		if(!$this->existUser()) {
			$this->_params['password']= md5($this->_params['password']);
			//add more field
			$this->_params['domain'] = '';
			$this->_params['uid'] = objid();
			foreach ($this->_fields as $key=>$val){
				if(!isset($this->_params[$key])){
					$this->_params[$key] = '';
				}
			}
			$r = Sharding_Redis::objectSet(K('user.user'), $this->_params,$this->_params['uid']);
			if($r) {
				$eventkey = K('event.user');
				Sharding_Redis::objectSet($eventkey, $this->_params['uid']);
				$this->existUser(true);
				return true;
			}
		}
		return false;
	}


	public function getUser()
	{
		$fields = array('uid','email','screenname','icon','domain','sign');
		return $this->_redis->hmGet(K('user.user').':'.$this->_params['uid'],$fields);
	}

	public function domainUser()
	{
		$domain = $this->_params['domain'];
		$id = $this->_redis->get(K('user.domain').':'.$domain);
		if(intval($id)){
			$this->_params['uid'] = $id;
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
		$fields = array('uid','email','screenname','icon','domain','sign');
		return $this->_redis->hmGet(K('user.user').':'.$this->_params['uid'],$fields);
	}
	
	public function uploadUser()
	{
		$u = new Common_Upload();
		$data = $u->upload();
		if($data !== false) {
			return $this->_redis->hmset(K('user.user').':'.$this->_params['uid'],array('icon'=>$data));
		}
		return false;
	}
	
	public function setdomainUser()
	{
		$domain = $this->_params['domain'];
		if($this->existDomain($domain)) {
			$uid = $this->_params['uid'];
			if($this->_redis->hmSet(K('user.user').':'.$uid,array('domain'=>$domain))) {
					$this->_redis->set(K('user.domain').':'.$domain,$uid);
			}
			return true;
		}
		return false;
	}
	
	private function existDomain($domain)
	{
		if($this->_redis->get(K('user.domain').':'.$domain)) {
			return false;
		}
		return true;
	}
	
	public function remarkUser()
	{
		$uid = $this->_params['uid'];
		$id = $this->_params['id'];
		$remark = $this->_params['remark'];
		return $this->_redis->set(K('user.remark').$uid.':'.$id,$remark);
	}

	public function signUser()
	{
		$sign = $this->_params['sign'];
		$uid = $this->_params['uid'];
		return $this->_redis->hmSet(K('user.user').':'.$uid,array('sign'=>$sign));
	}
	
}