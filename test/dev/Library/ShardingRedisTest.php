<?php

class ShardingRedisTest extends PHPUnit_Framework_TestCase
{

	

	public function testredis()
	{
		$key = 'abcd1234';
		$redis = Sharding_Redis::instance();
		$redis->set($key,$key);
		$r = $redis->get($key);
		$this->assertEquals(true,$r==$key);

	}


	public function testredisslave()
        {
                $key = 'abcd12345';
                $redis = Sharding_Redis::instance('redis','slave');
                $redis->set($key,$key);
                $r = $redis->get($key);
                $this->assertEquals(true,$r==$key);

        }


}
