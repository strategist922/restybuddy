<?php

class HardwareTest extends PHPUnit_Framework_TestCase
{
	protected static $id = '';
	protected static $params = '';
	public function testgetid()
	{
		$url = ROOT_URL.'/hardware/id';
		$params = array(
				'type'=>'ios',
		);
		self::$params = $params;
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		self::$id = $result['data'];
		$this->assertEquals(true,is_numeric($result['data']));
	}
	
	public function testgetidcheck()
	{
		
		$keys = "d:s:hardware:".self::$params['type'].":".date('Ymd');
		$r = Sharding_Redis::instance();
		$result = $r->sMembers($keys);
		var_dump(self::$id,$result);
		$this->assertEquals(true,in_array(self::$id,$result));		
	}
}
