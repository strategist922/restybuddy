<?php

class FirstpageTest extends PHPUnit_Framework_TestCase
{

	

	public function testfirstpage()
	{
		$url = ROOT_URL.'/firstpage'; 
		echo $url."\n";
		$arr = array('');
		$params = array(
				'memberID'=>'1000048990',
				'appID'=>'DX-201205091525394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a50',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
			       ); 
		$data = Common_Curl::post($url,$params);
		$data = json_decode($data,true);
		var_dump($data);
		$this->assertEquals(true,is_array($data));

	}


}
