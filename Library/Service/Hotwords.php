<?php

class Service_Hotwords {
	protected $_data;
	protected $_redius = 1000;
	
	protected $_redis = '';

	public function __construct($memberID,$appID,$cityID,$latitude,$longitude,$userHotwordNum,$areaHotwordNum,$cityHotwordNum)
	{
		$l = new Location_PlaceTool();
		$blockID = $l->calIndexByLatLon($latitude,$longitude);
		$blockIDs = $l->findIndex($latitude,$longitude,$this->_redius);
		if(!empty($blockIDs)) {
			$blockIDs = implode(',',$blockIDs);
			$blockIDs = rtrim($blockIDs,',');
		}else{
			$blockIDs = '';
		}	
		$this->_redis = Sharding_Redis::instance();

		$this->_data["listUserHotWords"] = $this->getUserWords($memberID,$appID,$userHotwordNum);
		$this->_data["listAreaHotWords"] = $this->getAreaWords($blockID,$areaHotwordNum,$blockIDs);
		$this->_data["listCityHotWords"] = $this->getcityWords($cityID,$cityHotwordNum);
	}


	public function getCityWords($cityID,$cityHotwordNum)
	{
		$key = 'c:cwords:'.$cityID;
		//$limit = -1;  //-1 all   500 return 500
		//get zset by score from redis
		$cityWords = $this->_redis->zRange($key,0,$cityHotwordNum,true);
		if(empty($cityWords) || !is_array($cityWords)) {
			$cityWords = array();
		}
		arsort($cityWords);
		return $cityWords;
	}

	public function getAreaWords($blockID,$areaHotwordNum,$blockIDs)
	{
		$key = 'c:awords:'.$blockID;
		//get nearly building
		$areaWords = $this->_redis->zRange($key,0,$areaHotwordNum,true);
		
		if(empty($areaWords) || !is_array($areaWords)) {
			$areaWords = array();
		}	
		arsort($areaWords);
		return $areaWords;
	}

	public function getUserWords($memberID,$appID,$userHotwordNum)
	{
		
                $key = 'c:uwords:'.$appID;
		$userWords = $this->_redis->zRange($key,0,$userHotwordNum,true);
		if(empty($userWords) || !is_array($userWords)) {
			$userWords = array();
		}		
		arsort($userWords);
		return $userWords;
	}


	public function data()
	{
		return $this->_data;
	}

}
