<?php
/**
 * redis class
 * do redis sharding  ad ==> m1   account==> m2
 */
class Sharding_Redis {
	static $_redis;

	private function __construct()
	{

	}

	public static function instance($group='redis',$node='master')
	{
		//need to add retry test
		if (!extension_loaded('redis')) {
			throw new Exception('phpredis EXTENSTION NOT LOADED!please check!');
		}

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

	public static function delInKeys($id,$group='redis',$node='master')
	{
		$objKey = K('key.keyset').':'.$id;
		$redis = self::instance($group,$node);
		$arrKey = $redis->sMembers($objKey);
		if(count($arrKey))
		{
			foreach ($arrKey as $one)
			{
				$keyType = substr($one, 2,2);
				switch($keyType)
				{
					case 'h:':
					case 'v:':
					case 'c:':
						$redis->delete($one);
						break;
					case 'l:':
					case 'u:':
						$redis->lRem($one,$id);
						break;
					case 'z:':
						$redis->zDelete($one,$id);
					case 's:':
						$redis->sRem($one,$id);			
						break;
				}
			}
		}
		//del u self
		$redis->del($objKey);
		return true;
	}

	/**
	 * delete key/value from redis
	 * @param mixed $keys if is string and include :* ,then get keys then delete it, else if is array then delete keys
	 * @param unknown_type $node
	 */
	public static function delObject($keys, $group = 'redis',$node='master') {
		$redis = self::instance($group,$node);
		$arrKeys = array();
		if (!is_array($keys)) {
			if (is_int(strpos($keys, ':*'))) {
				$arrKeys = $redis->keys($keys);
			}else{
				$arrKeys[] = $keys;
			}
		}else{
			if(count($keys)) {
				foreach($keys as $one){
					if (is_int(strpos($one, ':*'))) {
						$r = $redis->keys($one);
						if(is_array($r)) {
							$arrKeys = array_merge($arrKeys,$r);
						}
					}else{
						$arrKeys[] = $keys;
					}
				}
			}
		}
		return $redis->delete($arrKeys);
	}



	/**
	 * set redis key set
	 * @param type $key    keyname
	 * @param type $value key value
	 * @param type $id   id
	 * @param type $score
	 * @param type $length
	 * @param type $group
	 * @param type $master
	 * @return type
	 */
	public static function objectSet($key, $value, $id = '', $score = 0, $length = -1,$group = 'redis', $node = 'master') {
		$result = false;
		$r = self::instance($group,$node);
		$labelStroe = true;
		$funcName = '';
		$keyType = substr($key, 2,2);
		$objKey = $id ? $key.':'.$id : $key;
		switch ($keyType) {
			case 'h:':
				$funcName = 'hMset';
				$result = $r->$funcName($objKey, $value);
				break;
			case 'l:':
				$funcName = 'lPush';
				$result = $r->$funcName($objKey, $value);
				break;
			case 's:':
				$funcName = 'sAdd';
				$result = $r->$funcName($objKey, $value);
				break;
			case 'z:':
				$funcName = 'zAdd';
				$result = $r->$funcName($objKey, $score, $value);
				break;
			case 'i:':
				$funcName = 'zIncrBy';
				$result = $r->$funcName($objKey, $score, $value);
				break;
			case 'v:':
				$result = $r->set($objKey, $value);
			case 'c:':
				$value = intval($value);
				$funcName = $value > 0 ? 'incr' : 'decr';
				$result = $r->$funcName($objKey);
				break;
			case 'u:':
				$r->lRem($objKey, $value);
				$result = $r->lPush($objKey, $value);
				logTrace("$objKey   $value ULISTKEY ");
				if ($length > 0 && $result >= $length) {
					$r->rPop($objKey);
				}
				break;
			case 'd:':
				$result = $r->lRem($key, $value);
				$labelStroe = false;
				logTrace("$key $val  REMOVE %s", $result);
				break;
		}
		logTrace("objkey: $objKey ");
		//@TODO need optimized
		if ($result && $labelStroe) {
			if(is_array($value)) {
				$value = $value['id'];
			}
			if(is_string($value)) {
				$idStoreKey = K('key.keyset').':'.$value;
				logTrace("idstore: $idStoreKey");
				$r->sAdd($idStoreKey, $objKey);
			}
			
		}
		return $result;
	}





}
