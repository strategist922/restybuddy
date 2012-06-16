<?php

// +----------------------------------------------------------------------
// | WoShiMaiJia Projcet
// +----------------------------------------------------------------------
// | Copyright (c) 2010-2011 http://woshimaijia.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xinqiyang <xinqiyang@gmail.com>
// +----------------------------------------------------------------------

/**
 * Logic Base
 * 公共逻辑操作基础类
 * @author xinqiyang
 *
 */
class BaseLogic extends Base {

    /**
     * Service Base Add function
     * this is add to db point // to add to db
     * @param string $object
     * @param array $data
     */
    public static function add($object, $data) {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $r = $mysql->add($data);
            logDebug($mysql->getLastSql());
            if ($r) {
                $sql = "INSERT INTO object (id,object) VALUES({$data['id']},'{$object}')";
                $result = $mysql->execute($sql);
                if (!$result) {
                    logFatal('object insert error:' . $sql);
                }
            }
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * adds dataset to db
     * @param string $object
     * @param array $array dataset
     */
    public static function adds($object, $array) {
        $r = false;
        if (is_array($arra[0]) && count($array)) {
            foreach ($array as $val) {
                if(!empty($val)){
                    $r = self::add($object, $val);
                }
            }
        }
        return $r;
    }

    /**
     * Save method
     * 单纯的保存到DB
     * @param string $object
     * @param array $array
     */
    public static function save($object, $array, $where = '') {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $r = empty($where) ? $mysql->save($array) : $mysql->where($where)->save($array);
            logDebug($mysql->getLastSql());
            if ($r) {
                return $r;
            }
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * 保存后删除HASH CACHE
     * @TODO:这里需要检查
     * @param string $object
     * @param array $array
     * @param string $where
     */
    public static function saveClean($object, $array, $where = '') {
        if(isset($array['id'])) {
            $r = self::save($object, $array, $where);
            if ($r) {
                $key = KeysService::getKey(KeysService::$object, $object,$array['id']);
                if(!self::redisDeleteKeys(array($key))){
                    logFatal("DELETE KEY FROM REDIS ERROR:".$key);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * get object from db
     * @param string $object
     * @param bigint $id
     * @param string $fields
     * @param string $where
     */
    public static function get($object, $id, $fields = '*', $where = '') {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            if ($id) {
                $r = empty($fields) ? $mysql->find($id) : $mysql->field($fields)->find($id);
            } elseif ($where) {
                $r = empty($fields) ? $mysql->where($where)->find() : $mysql->where($where)->field($fields)->find();
            }
            $sql = $mysql->getLastSql();
            if ($sql) {
                logDebug(__CLASS__ . '/' . __FUNCTION__ . ':INFO  ' . mysql_real_escape_string($sql));
            }
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }
    
    
    public static function aget($object, $id, $fields = '', $where = 'status=0') {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            if ($id) {
                $r = empty($fields) ? $mysql->find($id) : $mysql->field($fields)->find($id);
            } elseif ($where) {
                $r = empty($fields) ? $mysql->where($where)->find() : $mysql->where($where)->field($fields)->find();
            }
            //@todo:发布的时候移除
            $sql = $mysql->getLastSql();
            if ($sql) {
                logDebug(__CLASS__ . '/' . __FUNCTION__ . ':INFO  ' . mysql_escape_string($sql));
            }
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * get objects from db
     * @param string $object
     * @param array $data 
     * @param string $fields
     */
    public static function gets($object, $array, $fields = '*') {
        $r = array();
        try {
            $mysql = MMysql::instance($object);
            $r = $mysql->field($fields)->where($array)->select();
            //发布的时候移除
            logDebug($mysql->getLastSql());
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * do query from the db
     * @param string $object
     * @param string $sql SQL
     */
    public static function query($object, $sql) {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $r = $mysql->query($sql);
            //发布的时候移除
            logDebug($mysql->getLastSql());
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * query row
     * @param string $object table name
     * @param string $where  id = xxx
     * @param string $fields * or id,username
     */
    public static function queryRow($object, $where, $fields = '*') {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $r = $mysql->field($fields)->where($where)->find();
            //发布的时候移除
            logDebug($mysql->getLastSql());
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * query Field 
     * query Field from mysql
     * @param string $object string
     * @param string $where  id=xxxx
     * @param string $field   fileda,fieldb
     */
    public static function queryField($object, $where, $field) {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $r = $mysql->getField($field, $where);
            //@todo 发布的时候移除
            logDebug($mysql->getLastSql());
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    /**
     * get objects from db
     * @param string $object
     * @param array $data
     * @param string $fields
     */
    public static function getsKeyID($object, $array, $fields = '*') {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $select['keyid'] = true;
            $r = $mysql->field($fields)->where($array)->select($select);
            //发布的时候移除
            logDebug($mysql->getLastSql());
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    public static function getsByPage($object, $where, $offset, $pageSize = 20, $fields = '*') {
        $r = false;
        try {
            $mysql = MMysql::instance($object);
            $select['keyid'] = true;
            $select['page'] = "$offset,$pageSize";
            $select['field'] = $fields;
            $select['where'] = $where;
            $r = $mysql->select($select);
            //@TODO:发布的时候删除
            logDebug($mysql->getLastSql());
        } catch (MException $e) {
            logFatal($e->__toString());
        }
        return $r;
    }

    

    /**
     * 从队列中对去需要处理的内容及消息进行处理
     * @param array $array  压入的数据
     * @param string $method 压入队列中的内容
     */
    public static function addToEventQueue($array, $method = '') {
        //@TODO:上线的时候需要移出
        return;
        $queue = Base::instance('Queue');
        $array['method'] = $method;
        $qr = $queue->put('event', Json::encode($array));
        return;
    }

    /**
     * delete object by array id or id
     * @param $array mixed string/array
     */
    public static function destory($id, $object = '') {
        $object = !empty($object) ? $object : self::queryField('object', "id=$id", 'object');
        if ($object && $id) {
            if(self::save($object, array('status' => 1), "id=$id"))
            {
                self::removeObjectRelations($id);
                return true;
            }
        }
        return false;
    }
    
    /**
     * 删除ID关联的KEY的，或者从KEY中移除
     * @param type $id 
     * @return type 
     */
    public static function removeObjectRelations($id)
    {
        $arrKey = array();
        //删除这个对象关联的缓存的key
        $arrKey = self::redisGetKeyValues(KeysService::$set, KeysService::$keyIdKeysStore,$id);
        if(count($arrKey))
        {
            foreach ($arrKey as $one)
            {
                if(is_int(strpos($one, KeysService::$ulist))){
                    self::redisSetKeyValues(KeysService::$remove, $one, $id);
                }else{
                    self::redisDeleteKeys(array($one));
                }
            }
        }
        return ;
    }

    /**
     * delete key/value from redis
     * @param mixed $keys if is string and include * ,then get keys then delete it, else if is array then delete keys
     * @param unknown_type $node
     */
    public static function redisDeleteKeys($keys, $node = 'redis') {
        $redis = MRedis::instance($node);
        if (!is_array($keys)) {
            if (is_int(strpos($keys, '*'))) {
                $keys = $redis->keys($keys);
            }
        }
        return $redis->delete($keys);
    }

    //新写的获取缓存并自动重建的方法
    /**
     * 获取redis中数据的方法
     * @param mixed $idList  array/bigint/''
     * @param string $keyType
     * @param string $key
     * @param int $start
     * @param int $end
     * @param int $withScore
     * @param string $node
     */
    public static function redisGetKeyValues($keyType, $key, $idList = '', $start = 0, $end = -1, $withScore = true, $node = 'redis') {
        $arr = array();
        $r = MRedis::instance($node);
        $start = ($start === 0) ? 0 : (($end * $start - $end == 0) ? 0 : $end * $start - $end );
        $end = ($end === -1) ? -1 : (($start + $end) > 1 ? $start + $end - 1 : 0 );
        if (!is_array($idList)) {
            $idList = array($idList);
        }
        if (is_array($idList) && !empty($idList)) {
            foreach ($idList as $val) {
                //拼接对象的key
                $objKey = KeysService::getKey($keyType, $key, $val);
                //@todo:发布的时候移除
                logDebug($objKey . ' '.$key.' '.$val);
                //去获取对象的Key的内容，如果未获取到则进行重建数据
                $funcName = '';
                $val = empty($val) ? 0 : $val;
                switch ($keyType) {
                    case 'h:':
                        $funcName = 'hGetAll'; //$redis->hGetAll('key');  //获取单个的hash对象
                        $m = $r->$funcName($objKey);
                        $arr[$val] = $m;
                        break;
                    case 'l:':
                    case 'u:':
                        $funcName = 'lRange'; //$redis->lRange('',0,-1); //获取列表中所有的对象
                        $m = $r->$funcName($objKey, $start, $end);
                        //logDebug(__FUNCTION__.'    '.$start.'  '.$end,$m,'----');
                        if ($m) {
                            $arr[$val] = $m;
                        }
                        break;

                    case 's:':
                        $funcName = 'sMembers'; //$redis->sRange('key',0,-1); //获取集合中所有的对象
                        $m = $r->$funcName($objKey);
                        if ($m) {
                            $arr[$val] = $m;
                        }
                        break;
                    case 'z:':
                        $funcName = 'zRange'; //$redis->zRange('key',0,-1,true); //获取排序集合中所有的对象WITH score
                        $m = $r->$funcName($objKey, $start, $end, $withScore);
                        if ($m) {
                            $arr[$val] = $m;
                        }
                        break;
                    case 'v:':
                    case 'c:':
                        $funcName = 'get'; //$redis->get('key');  //获取单个 字符串或者统计的对象
                        $m = $r->$funcName($objKey);
                        $arr[$val] = rtrim($m, ',');
                        logDebug(__CLASS__ . '/' . __FUNCTION__ . ' %s  %s', $objKey, $arr[$val]);
                        break;
                    case 'a:':
                        $arr[$val] = $r->lSize($key);
                        logDebug("COUNT KEY %s ,VALUE:%s", $key, $arr[$val]);
                        break;
                }
            }
            //如果就只有一个就只返回一个
            if (count($arr) == 1) {
                $arr = current($arr);
            }
            return $arr;
        }
        return false;
    }

    /**
     * 设置redis的key和值
     * @param type $keyType key的类型
     * @param type $key    key的名称
     * @param type $value 设置的值
     * @param type $id   key相关的id
     * @param type $score 
     * @param type $length 
     * @param type $node
     * @return type 
     */
    public static function redisSetKeyValues($keyType, $key, $value, $id = '', $score = 0, $length = -1, $node = 'redis') {
        $result = false;
        $r = MRedis::instance($node);
        //拼接对象的key
        $objKey = KeysService::getKey($keyType, $key, $id);
        //@todo:发布的时候移除
        logDebug("objkey:%s  key:%s id:%s value:%s TYPE:%s", $objKey, $key, "$id", $value, $keyType);
        //去获取对象的Key的内容，如果未获取到则进行重建数据
        $labelStroe = true;
        $funcName = '';
        switch ($keyType) {
            case 'h:':
                $funcName = 'hMset'; //将一个对象写入一个key中，value是一个数组
                $result = $r->$funcName($objKey, $value);
                break;
            case 'l:':
                $funcName = 'lPush'; //value 是一个bigint
                $result = $r->$funcName($objKey, $value);
                break;
            case 's:': 
                $funcName = 'sAdd'; //写入集合中所有的对象
                $result = $r->$funcName($objKey, $value);
                break;
            case 'z:':
                $funcName = 'zAdd';
                $result = $r->$funcName($objKey, $score, $value);
                break;
            case 'v:':  //@todo 这里设置一个bigint时候会报错，需要把value转化成string才行
                $result = $r->set($objKey, $value . ',');
            case 'c:':
                $value = intval($value);
                $funcName = $value > 0 ? 'incr' : 'decr';
                $result = $r->$funcName($objKey);
                break;
            case 'u:':
                $r->lRem($objKey, $value);
                $result = $r->lPush($objKey, $value);
                logDebug("ULISTKEY:".$objKey);
                if ($length > 0 && $result >= $length) {
                    $r->rPop($objKey);
                }
                break;
            case 'd:': //从list中移除一个
                $result = $r->lRem($key, $value);
                $labelStroe = false;
                logDebug("REMOVE %s", $result);
                break;
        }
        //如果操作成功且是需要存储的，则将KEY加入到ID的集合里面,作为删除的时候使用
        if ($result && $labelStroe) {
            $idStoreKey = KeysService::getKey(KeysService::$set, KeysService::$keyIdKeysStore, $id);
            $r->sAdd($idStoreKey, $objKey);
        }
        return $result;
    }

    /**
     * 获取数据库中未删除的数据总数
     * @param string $object
     * @return int 
     */
    public static function count($object) {
        $count = self::queryField($object, "status=0", 'count(*)');
        if ($count) {
            return $count;
        }
        return 0;
    }
    
    /**
     * 返回一个key中存储的元素的个数
     * @param type $keyType
     * @param type $key
     * @param type $id
     * @return type 
     */
    public static function redisCount($keyType,$key,$id='')
    {
        return self::redisGetKeyValues(KeysService::$count,  KeysService::getKey($keyType,$key,$id));
    }


    
    public static function getMaxId($object) {
        return self::queryField($object, 'status=0 ', 'max(id) as id');
    }

    public static function isExist($object,$field, $value) {
        return self::get($object, '', '*', "$field='$value'");
    }
}
