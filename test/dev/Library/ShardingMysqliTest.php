<?php

class ShardingMysqliTest extends PHPUnit_Framework_TestCase
{

	

	public function testmysql()
	{
		$sql = "SELECT * FROM search_searchwords limit 1";
		$m = Sharding_Mysqli::instance();
		$r = $m->fetchRow($sql);
		$this->assertEquals(true,is_array($r));

	}
	

	public function testmysqlfetchall()
        {
                $sql = "SELECT * FROM search_searchwords limit 10";
                $m = Sharding_Mysqli::instance();
                $r = $m->fetchAll($sql);
                $this->assertEquals(true,count($r)==10);

        }
	
	

}
