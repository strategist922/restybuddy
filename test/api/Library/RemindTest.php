<?php

class RemindTest extends PHPUnit_Framework_TestCase
{

	

	public function testsetcount()
	{
		$params = array(
				'id'=>123,
				'type'=>'stream',
				'score'=>'4'
		);
		$result = Q('Business_Remind',$params,'setcount');
		var_dump($result);
		$this->assertEquals(true,$result);
	}

	public function testsetcountcomment()
	{
		$params = array(
				'id'=>123,
				'type'=>'comment',
				'score'=>'45'
		);
		$result = Q('Business_Remind',$params,'setcount');
		var_dump($result);
		$this->assertEquals(true,$result);
	}

	public function testunread()
	{
		$params = array('id'=>123);
		$result = Q('Business_Remind',$params,'unread');
		var_dump($result);
		$this->assertEquals(true,is_array($result));

	}


	public function testsetcountzero()
	{
		$params = array(
				'id'=>123,
				'type'=>'comment',
		);
		$result = Q('Business_Remind',$params,'setcountZero');
		//$result = Q('Business_Remind',$params,'unread');
		var_dump($result);
		$this->assertEquals(true,true);

	}
	
	
	public function testsetcountzerostream()
	{
		$params = array(
				'id'=>123,
				'type'=>'comment',
		);
		//$result = Q('Business_Remind',$params,'setcountZero');
		$result = Q('Business_Remind',$params,'unread');
		var_dump($result);
		$this->assertEquals(true,$result['comment'] ==0);
	
	}



}
