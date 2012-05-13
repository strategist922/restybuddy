<?php
/**
* 用户浏览过的数据记录接口
*
* 
*/

class Service_Userview {

	protected $_data = array();

	public function __construct($memberID,$appID,$cityID,$latitude,$longitude,$type,$id)
	{
		//写入count的内容
		$c = new Count_Deal(__CLASS__);
		$c->setQueue($memberID,$appID,$cityID,$latitude,$longitude,$type,$id,time());	
	}


	public function data()
	{
		return array();
	}

}
