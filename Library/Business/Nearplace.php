<?php
/**
* near places
*/
class Business_Nearplace
{
	protected $_blockID;
	protected $_blockIDs;
	protected $_mysql;	
	protected $_redius;

	public function __construct($latitude,$longitude,$redius=500)
	{
		$this->_redius = $redius;
		$l = new Location_PlaceTool();
                $blockID = $l->calIndexByLatLon($latitude,$longitude);
                $blockIDs = $l->findIndex($latitude,$longitude,$this->_redius);
                if(!empty($blockIDs)) {
                        $blockIDs = implode(',',$blockIDs);
                        $blockIDs = rtrim($blockIDs,',');
                }else{
                        $blockIDs = '';
                }
		$this->_blockIDs = $blockIDs;
		$this->_blockID = $blockID;
		
		$this->_mysql = Sharding_Mysqli::instance('search');

	}

	//get near blocks
	public function getNearBlockID()
	{
		return $this->_blockIDs;	
	}
	
	//get places
	public function getPlaceByBlockID($field='id')
	{
		//blockIndex  ==>  placeIDs   
	        //$sql = "SELECT $field FROM search_place where blockIndex=".$this->_blockID;
		$sql = "SELECT $field FROM ".PRODUCTDBNAME.".place where blockIndex=".$this->_blockID;
		error_log( $sql."\n");
		return $this->_mysql->fetchColumn($sql);   	
	}

	//get all place
	public function getAllPlaces($field='id')
	{
		if(!empty($this->_blockIDs)) {
			//$sql = "SELECCT $field FROM search_place where blockIndex in (".$this->_blockIDs.")";
			$sql = "SELECT $field FROM ".PRODUCTDBNAME.".place where blockIndex in (".$this->_blockIDs.")";
			error_log( $sql."\n");
			return $this->_mysql->fetchColumn($sql);
		}
		return array();
	}
	
	
}

