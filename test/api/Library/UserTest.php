<?php

class UserTest extends PHPUnit_Framework_TestCase {
	/*
	public function testAddNewUser()
	{
		$url = ROOT_URL.'/signup/signup';
		$params = array(
				'email'=>'xinqiyang@gmail.com',
				'screenname'=>'xinqiyang',
				'password'=>'heihei',
				'lat'=>'86',
				'long'=>'116',
		);
		echo $url."\n";
		$r = Common_Curl::post($url,$params);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,$result['data']);
	}
	*/
	public function testupdatesign()
	{
		$url = ROOT_URL.'/user/sign';
		$params = array(
				'uid'=>'13419130990072891',
				'sign'=>'xinqiyang s sign',
				'lat'=>'86',
				'long'=>'116',
		);
		echo $url."\n";
		$r = Common_Curl::post($url,$params);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,$result['data']);
	}
	//update sign
	
	
	public function testinfo()
	{
		$url = ROOT_URL.'/user/info';
		$params = array(
				'uid'=>'13419130990072891',
		);
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,!empty($result['data']));
	}
	
	
	
	public function testcounts()
	{
		$url = ROOT_URL.'/user/counts';
		$params = array(
				'uid'=>'13419130990072891',
		);
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,!empty($result['data']));
	}
	
	public function testshow()
	{
		$url = ROOT_URL.'/user/show';
		$params = array(
				'uid'=>'13419130990072891',
		);
		$url = addParamToUrl($url,$params);
		echo $url."\n";
		$r = Common_Curl::get($url);
		$result = json_decode($r,true);
		var_dump($result);
		$this->assertEquals(true,!empty($result['data']));
	}
	
	
	
	
	
	
}