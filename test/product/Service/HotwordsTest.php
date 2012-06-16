<?php

class HotwordsTest extends PHPUnit_Framework_TestCase
{

	

	public function testhotwords()
	{
		$url = ROOT_URL.'/hotwords'; 
		//echo $url."\n";
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
			       ); 
		$data = Common_Curl::post($url,$params);
		var_dump($data);
		$r = 1;
		$this->assertEquals(true,$r>0);

	}


}
