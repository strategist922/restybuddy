<?php

class TimeTest extends PHPUnit_Framework_TestCase
{

	

	public function testisweekend()
	{	
		
		$r = Common_Time::isWeekend();
		$this->assertEquals(true,$r);

	}

	public function testisweekendnum()
	{
		$r = Common_Time::isWeekend();
		$this->assertEquals(true,$r);

	}

	
	public function testistimeperiod()
        {
		$r = Common_Time::nowhours();
		var_dump($r);
		$this->assertEquals(true,true);

        }
	


}
