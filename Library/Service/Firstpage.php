<?php

class Service_Firstpage {

	protected $_data = array();
	protected $_redis = 1000;

	public function __construct($memberID,$appID,$cityID,$latitude,$longitude)
	{

		//进行场景分析后传入参数，参数执行具体的推荐内容
		$l = new Location_PlaceTool();
		$blockID = $l->calIndexByLatLon($latitude,$longitude);
		$blockIDs = $l->findIndex($latitude,$longitude,$this->_redis);

		//推荐菜	
		$this->getProduct($cityID,$blockID,$appID,$memberID);
		//消费街
		$this->getShoppingStreets($cityID,$blockID,$appID,$memberID);
		//商家
		$this->getPlaces($cityID,$blockID,$appID,$memberID);
		//优惠
		$this->getActivities($cityID,$blockID,$appID,$memberID);
	}


	public function getProduct($ciityID,$blockID,$appID,$memberID)
	{

		$r = new Recommender_Product();
		$this->_data['listProducts'] = $r->data();
	}

	public function getShoppingStreets($ciityID,$blockID,$appID,$memberID)
	{

		$listShoppingStreets = array(55,66,77);
		$this->_data['listShoppingStreets'] = $listShoppingStreets;
	}


	public function getPlaces($ciityID,$blockID,$appID,$memberID)
	{
		$listPlaces = array(77,88);

		$this->_data['listPlaces'] = $listPlaces;
	}

	public function getActivities($ciityID,$blockID,$appID,$memberID)
	{

		$listActivities = array(
				array('activityID'=>999,'placeID'=>111),
				array('activityID'=>999,'placeID'=>111),
				);
		$this->_data['listActivities'] = $listActivities;
	}


	public function data()
	{
		return $this->_data; 
	}
}

