<?php

class UsersearchTest extends PHPUnit_Framework_TestCase
{

	

	public function testusersearch()
	{
		$url = ROOT_URL.'/usersearch'; 
			echo $url."\n";
		$arr = array('');
		$params = array(
				'memberID'=>'1000048990',
				'appID'=>'DX-201205091525394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a50',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				'keyword'=>'东北',// '烤鸭',// '永和豆浆', //全聚德  北京烤鸭  东北人家   
			       ); 
		$data = Common_Curl::post($url,$params);
		var_dump($data);
		$r = 1;
		$this->assertEquals(true,$r>0);

	}


}
