<?php

class TagsTest extends PHPUnit_Framework_TestCase
{



	public function testcreate()
	{
		$url = ROOT_URL.'/tags/create';
		$params = array(
				'id'=>'88888',
				'tag'=>'tag',
				'uid'=>'12343'
		);
		echo $url."\n";
		$r = Common_Curl::post($url, $params);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,$result['data']);
	}

	public function testtags()
	{
		$url = ROOT_URL.'/tags/tags';
		$params = array(
				'id'=>'88888',
				'n'=>10,
		);
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,is_array($result['data']));
	}


	public function testtagsbatch()
	{
			
		$url = ROOT_URL.'/tags/tagsbatch';
		echo $url."\n";
		$params = array(
				'id'=>'12343,12345',
				'n'=>10,
		);
		$url = addParamToUrl($url,$params);
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,count($result['data']) >0 );
	}
	
	public function testhot()
	{
		$url = ROOT_URL.'/tags/hot';
		$params = array('min'=>0,'max'=>100);
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,is_array($result['data']));
	}

	public function testdestroy()
	{
		$url = ROOT_URL.'/tags/destroy';
		echo $url."\n";
		$params = array(
				'tag'=>'tag'
				);
		$url = addParamToUrl($url,$params);
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,$result['data']);
	}
	
	
	public function testhotdel()
	{
		$url = ROOT_URL.'/tags/hot';
		$params = array('min'=>0,'max'=>100);
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,empty($result['data']));
	}

}
