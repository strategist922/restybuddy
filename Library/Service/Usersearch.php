<?php

class Service_Usersearch {

	protected $_data = array();
	protected $_redis = 1000;
	protected $_blockIds = '';

	public function __construct($memberID,$appID,$cityID,$latitude,$longitude,$keyword) {

		$l = new Location_PlaceTool();
		$blockID = $l->calIndexByLatLon($latitude,$longitude);
		$blockIDs = $l->findIndex($latitude,$longitude,$this->_redis);
                if(!empty($blockIDs)) {
			$blockIDs = implode(',',$blockIDs);
			$blockIDs = rtrim($blockIDs,',');
			//error_log($blockIDs);
		}
		$this->search($cityID,$blockID,$blockIDs,$keyword);
		//recount count
		//@TODO need to think about return data to store
		$c = new Count_Deal(__CLASS__);
		$c->setQueue($memberID,$appID,$cityID,$blockID,$blockIDs,$keyword,$this->_data);	
	}

	

	public function search($cityID,$blockID,$blockIDs,$keyword)
	{
		$s = new Search_Query();
		$prefix = $s->mysql()->escape($keyword);
		$s->setField('id');
		$s->setDatabase('dx16.place join sphinx.dx on (dx.sphinx_id = place.id)');
		$s->setQuery($prefix);
		$s->setMatches(500);
		$s->setIndex('place,place_delta');
		$s->setMode('any');
		$s->setFilter("cityId,$cityID");
		//@todo: NEED TO DEBUG	
		if(!empty($blockIDs)) {
			//add more block , result less
			//$s->setRange("blockIndex,$blockIDs");
		}
		$s->setLimit(100);
		//error_log("aaa");
		try {
			$this->_data = $s->fetchColumn();
		}catch(Exception $e) {
			error_log(var_export($e,true));
		}
		//error_log(var_export($result),true);
		//error_log("bbb");
		if(empty($this->_data) || !is_array($this->_data))
		{
			$this->_data = array();
		}
		return ;

	}



	
	public function data()
	{
		return array('listPlaceIDS'=>$this->_data);
	}


}
