<?php
/**
 * 集群redis类
 * 对象垂直切分  ad ==> m1   account==> m2
 */
class Sharding_Redis {
	static $_redis;

	private function __construct()
	{

	}

	public function instance($group='redis',$node='master')
	{
		if(!isset(self::$_redis[$node.'.'.$group]))
		{
			$machine = C("$group.$node");
			if(empty($machine)) {
				throw new Exception("get $group.$node node config error");
			}
			if(($node !== 'master') && is_int(strpos(rtrim($machine['host'],','),',')))
			{
				$expand = explode(',',$machine['host']);
				if(count($expand)) {
					$key = array_rand($expand);
					$machine['host'] = $expand[$key];
				}
			}
			$host = $machine['host'];
			$port = $machine['port'];
			//need to add retry test
			if (!extension_loaded('redis')) {
         	   		throw new Exception('phpredis EXTENSTION NOT LOADED!please check!');
        		}
			try {
				$redis = new Redis();
				$redis->connect($host,$port);
				self::$_redis[$node.'.'.$group] = $redis;
			}catch (Exception $e) {
				throw $e;
			}
		}
		return self::$_redis[$node.'.'.$group];   
	}

}
