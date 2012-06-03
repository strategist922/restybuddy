<?php

class Business_Stream {

	protected $_params = '';
	protected $_redis = '';
	
	
	public function __construct($params='')
	{
		$this->_params = $params;
		$this->_params['lat'] = isset($this->_params['lat']) ? $this->_params['lat'] : '';
		$this->_params['long'] = isset($this->_params['long']) ? $this->_params['long'] : '';
		$this->_params['attache'] = isset($this->_params['attache']) ? $this->_params['attache'] : '';
		$this->_params['annotations'] = isset($this->_params['annotations']) ? $this->_params['annotations'] : '';
		$this->_redis = Sharding_Redis::instance();
	}

	private function isPost($value='')
	{
		$key = K('user.lastpost');
		if(empty($value)) {
			$lastpost = $this->_redis->get($key.':'.$this->_params['uid']);
			if($lastpost === $this->_params['stream'])
			{
				return true;
			}
			return false;
		}
		return Sharding_Redis::objectSet($key,$value,$this->_params['uid']);
	}

	public function updateStream()
	{
		if(!$this->isPost()) {
			$this->_params['id'] = objid();
			//$key = K('stream.stream');
			$r = Sharding_Redis::objectSet(K('stream.stream'), $this->_params,$this->_params['id']);
			if($r) {
				//@TODO: remove to event dealer???
				//update stream
				$this->isPost($this->_params['stream']);
				//add to outbox and add to inbox
				//$outbox = K('user.outbox').':'.$this->_params['uid'];
				Sharding_Redis::objectSet(K('user.outbox'), $this->_params['id'],$this->_params['uid']);
				//$inbox = K('user.inbox').':'.$this->_params['uid'];
				Sharding_Redis::objectSet(K('user.inbox'), $this->_params['id'],$this->_params['uid']);
				//add to event
				$eventkey = K('event.stream');
				Sharding_Redis::objectSet($eventkey, $this->_params['id'].':'.$this->_params['uid']);
				return true;
			}
		}
		return false;		
	}
	
	private function isUpload()
	{
		$u = new Common_Upload();
		$data = $u->upload();
		if($data !== false) {
			$this->_params['attache'] = $data;
		}
		return;
	}


	public function uploadStream()
	{
		$this->isUpload();
		return $this->updateStream();
	}

	
	public function destoryStream()
	{
		$eventkey = K('event.stream');
		$this->_redis->lRem($eventkey,$this->_params['id']);
		//del id in his keys
		Sharding_Redis::delInKeys($this->_params['id']);
		return true;
		
	}

	
	public function getStream()
	{
		$key = K('stream.stream').':'.$this->_params['id'];
		return $this->_redis->hGetAll($key);
	}
	
	public function repostStream()
	{
		$this->_params['pid'] = $this->_params['id'];
		return $this->updateStream();
	}
	
	 
	
}