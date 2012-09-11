<?php

class StreamTest extends PHPUnit_Framework_TestCase {
	
	
	public function teststream()
	{
		$url = ROOT_URL.'/stream/update';
		$params = array(
				'uid'=>'13419130990072891',
				'stream'=>'xinqiyang s sign',
				'lat'=>'86',
				'long'=>'116',
				
		);
		echo $url."\n";
		$r = Common_Curl::post($url,$params);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,$result['data']);
	}
	
	
	
	
}