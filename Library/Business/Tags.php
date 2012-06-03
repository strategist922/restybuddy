<?php

class Business_Tags {

	protected $_params = array();
	protected $_redis = '';

	public function __construct($params='')
	{
		$this->_params = $params;
		$this->_redis = Sharding_Redis::instance();
	}

	public function create()
	{
		$id = $this->_params['id'];
		$uid = $this->_params['uid'];
		$tag = $this->_params['tag'];
		
		$tagarr = explode(' ',$tag);
		$key = K('tag.objtag').':'.$id;
		$userkey = K('tag.usertag').':'.$uid;
		$tagkey = K('tag.tagkey');
		$hotkey = K('tag.hotkey');
		foreach ($tagarr as $one)
		{
			$one = trim($one);
			if($one) {
				$tagmd5 = md5($one);
				$tagkey .= ':'.$tagmd5;
				$tr = $this->_redis->set($tagkey,$one);
				$kr = $this->_redis->zAdd($key,1,$tagmd5);
				$utr = $this->_redis->zAdd($userkey,1,$tagmd5);
				$htr = $this->_redis->zAdd($hotkey,1,$tagmd5);
			}
		}
		return true;
	}

	public function destroy()
	{
		//$id = $this->_params['id'];
		//$uid = $this->_params['uid'];
		$tag = $this->_params['tag'];
		$md5 = md5($tag);
		//$key = K('tag.objtag').':'.$id;
		//$userkey = K('tag.usertag').':'.$uid;
		//$this->_redis->zrem($key,$md5);
		//$this->_redis->zrem($userkey,$md5);
		return $this->_redis->del(K('tag.tagkey').':'.$md5);
	}

	//get object tag
	public function tags()
	{
		$n = $this->_params['n'];
		$id = $this->_params['id'];
		$key = K('tag.objtag').':'.$id;
		$md5keys = $this->_redis->zrevrange($key,0,$n);
		$keyprefix = K('tag.tagkey');
		$keys = getMultiKeys($keyprefix, $md5keys);
		return $this->_redis->mget($keys);
	}

	//get multy user tags
	public function tagsbatch()
	{
		$arr = array();
		$n = $this->_params['n'];
		$userarr = explode(',',$this->_params['id']);
		$keyprefix =K('tag.usertag');
		$tagprefix = K('tag.tagkey');
		foreach ($userarr as $uid)
		{
			$userkey = $keyprefix.':'.$uid;
			$md5keys = $this->_redis->zrevrange($userkey,0,$n);
			$keys = getMultiKeys($tagprefix, $md5keys);
			$arr[$uid] = $this->_redis->mget($keys);
		}
		return $arr;
	}
	
	public function hot()
	{
		$hotkey = K('tag.hotkey');
		$min = isset($this->_params['min']) ? $this->_params['min'] : 0;
		$max = isset($this->_params['max']) ? $this->_params['max'] : 50;
		$md5keys = $this->_redis->zrangebyscore($hotkey,$min,$max);
		$tagprefix = K('tag.tagkey');
		$keys = getMultiKeys($tagprefix, $md5keys);
		$tags = $this->_redis->mget($keys);
		$r = array();
		if($tags) {
			foreach ($tags as $tag) {
				if($tag) {
					$r[] = $tag;
				}
			}
		}
		return $r;
	}



}