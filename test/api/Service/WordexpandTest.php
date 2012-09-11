<?php

class WordexpandTest extends PHPUnit_Framework_TestCase
{


	public function testwordexpand_null()
	{
		$url = ROOT_URL.'/wordexpand';
		echo $url."\n"; 
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				'prefix'=>'',// 'bei',//'东来',// '东直',
			       ); 
		$data = Common_Curl::post($url,$params);
		$r = json_decode($data,true);
		//var_dump($r);
		$this->assertEquals(true,is_array($r));

	}

	public function testwordexpand_oneword()
	{
		$url = ROOT_URL.'/wordexpand'; 
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				'prefix'=>'永',// 'bei',//'东来',// '东直',
			       ); 
		$data = Common_Curl::post($url,$params);
		$r = json_decode($data,true);
		//var_dump($r);
		$this->assertEquals(true,is_array($r));

	}


	public function testwordexpand_twoword()
	{
		$url = ROOT_URL. '/wordexpand'; 
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				'prefix'=>'永和',// 'bei',//'东来',// '东直',
			       ); 
		$data = Common_Curl::post($url,$params);
		$r = json_decode($data,true);
		//var_dump($r);
		$this->assertEquals(true,is_array($r));

	}
	
	public function testwordexpand_threeword()
	{
		$url = ROOT_URL.'/wordexpand'; 
			echo $url."\n";
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				'prefix'=>'永和大',// 'bei',//'东来',// '东直',
			       ); 
		$data = Common_Curl::post($url,$params);
		
		$r = json_decode($data,true);
                //var_dump($r);
                $this->assertEquals(true,is_array($r));
		

	}
        
  	public function testwordexpand_fourword()
	{
		$url = ROOT_URL.'/wordexpand'; 
			echo $url."\n";
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				'prefix'=>'永和大王',// 'bei',//'东来',// '东直',
			       ); 
		$data = Common_Curl::post($url,$params);
		$r = json_decode($data,true);
                //var_dump($r);
                $this->assertEquals(true,is_array($r));
	}


	public function testwordexpand_paramserror()
	{
		$url = ROOT_URL.'/wordexpand'; 
			echo $url."\n";
		$params = array(
				'memberID'=>'1000048973',
				'appID'=>'DX-201205091535394832-0C74C225A169+4bba6e00d9920d617bad2ac9c8ede0c37f045a59',
				'cityID'=>'2',
				'latitude'=>'39.939579',
				'longitude'=>'116.414309',
				//'prefix'=>'永和',// 'bei',//'东来',// '东直',
			       ); 
		$data = Common_Curl::post($url,$params);
		$r = json_decode($data,true);
                //var_dump($r);
                $this->assertEquals(true,is_array($r));
	}
	


}
