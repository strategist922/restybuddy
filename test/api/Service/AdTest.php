<?php

class AdTest extends PHPUnit_Framework_TestCase
{

	public function testupdate()
	{
		$url = ROOT_URL.'/ad/update';
		$params = array(
				'module'=>'index',
				'action'=>'index',
				'position'=>'1',
				'adcode'=>'<p>this is a block of ad code</p>',
		);
		$res = Common_Curl::post($url, $params);
		echo $res."\n";
		$result = json_decode($res,true);
		var_dump($result);
		$this->assertEquals(true,$result['code']==0);
	}

	

	public function testshow()
	{
		$url = ROOT_URL.'/ad/show';
		$params = array(
				'module'=>'index',
				'action'=>'index',
				'position'=>'1',
		);
		$res = Common_Curl::post($url, $params);
		$result = json_decode($res,true);
		var_dump($result);
		$this->assertEquals(true,!empty($result['data']));
	}

	public function testlist()
	{
		$url = ROOT_URL.'/ad/list';
		$params = array(
				'module'=>'index',
				);
		$res = Common_Curl::post($url, $params);
		$result = json_decode($res,true);
		var_dump($result);
		$this->assertEquals(true,is_array($result['data']));
	}

	public function testdestroy()
	{
		$url = ROOT_URL.'/ad/destroy';
		$params = array(
				'module'=>'index',
				'action'=>'index',
				'position'=>'1',
		);
		$res = Common_Curl::post($url, $params);
		$result = json_decode($res,true);
		var_dump($result);
		$this->assertEquals(true,$result['data']==true);
	}

}