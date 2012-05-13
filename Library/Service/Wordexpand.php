<?php

class Service_Wordexpand {

	protected $_data = array();

	public function __construct($memberID,$appID,$cityID,$latitude,$longitude,$prefix)
	{
		$prefix = trim($prefix);
		if(!empty($prefix))  {
			$s = new Search_Query();
                        $prefix = $s->mysql()->escape($prefix);
			$s->setField('name');
			$s->setDatabase('dx16.place join sphinx.dx on (dx.sphinx_id = place.id)');
			$s->setQuery('^'.$prefix);
			$s->setIndex('wordexpand');
			$s->setMode('phrase');
			$s->setFilter("cityId,$cityID");
			$s->setLimit(10);
			$this->_data = $s->fetchColumn();
			//var_dump($this->_data);
			if(empty($this->_data) || !is_array($this->_data))
			{
				$this->_data = array();
			}
		}else{
			//read redis to get keywords
			if(!empty($appID)) {
				$key = 's:wordexpand:'.$appID;
				$r = Sharding_Redis::instance();
				$sort = array('limit'=>array(0,10));
				$this->_data = $r->sort($key,$sort);
				if(empty($this->_data) || !is_array($this->_data)) {
					$this->_data = array();
				}//else{ 
					//krsort($this->_data);
				//}
				
			}

		}

	}


	public function data()
	{
		return array('listKeyWords'=>$this->_data);
	}

}
