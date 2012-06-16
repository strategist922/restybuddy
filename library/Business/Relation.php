<?php

class Business_Relation {
	
	protected $_params = '';
	protected $_redis = '';
	
	public function __construct($params)
	{
		$this->_params = $params;
		$this->_redis = Sharding_Redis::instance();
	}
	
	public function setRelation()
	{
		$keyFollower = K('relation.follower');
		Sharding_Redis::objectSet($keyFollower, $this->_params['uid'],$this->_params['id']);
		$keyFollowing = K('relation.following');
		Sharding_Redis::objectSet($keyFollowing, $this->_params['id'],$this->_params['uid']);
		return true;
	}
	
	
	public function destoryRelation()
	{
		$keyFollower = K('relation.follower');
		$this->_redis->lRem($keyFollower.':'.$this->_params['id'], $this->_params['uid']);
		$keyFollowing = K('relation.following');
		$this->_redis->lRem($keyFollowing.':'.$this->_params['uid'], $this->_params['id']);
		return true;
	}
	
	public function getFollowing()
	{
	
	}
	
	public function getFollower()
	{
	
	}
	
	
	public function getFollowingIds()
	{
		$keyFollowing = K('relation.following');
		$key = $keyFollowing.':'.$this->_params['uid'];
		return $this->_redis->lRange($key,0,-1);
	}
	
	public function getFollowerIds()
	{
		$keyFollower = K('relation.follower');
		$key = $keyFollower.':'.$this->_params['uid'];
		return $this->_redis->lRange($key,0,-1);
	}
	
	
}
